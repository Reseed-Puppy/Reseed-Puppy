from cache import add_seed_to_cache,remove_cached_values
from db import getSiteInfo
from log import writeLog
from transmission_rpc import Client
import bencodepy
import requests
import hashlib
import os
logger = writeLog('tr_logger', '/reseed-puppy/python/log/reseed.log')
def connect_to_transmission(tr):
  try:
    c = Client(host=tr['url'], port=tr['port'], username=tr['username'], password=tr["password"])
  except:
    logger.error('transmission连接失败')
    return 0
  return c

# 辅种主函数
def trseed(tr):
  isaction = 'false' if tr['isaction'] == 2 else 'true'
  # 配置transmission的连接参数以及尝试连接
  if tr['url'] == None or tr['url'] == '':
      logger.error('请先配置transmission的连接参数')
      return 0
  else:
    c = connect_to_transmission(tr)
    if( c == 0):
      return 0
    # 配置种子文件夹路径
    folder_path = tr['dir']
    # 记录所有种子的pieces_hash
    pieces_hash_list = []
    # 记录所有可辅种的种子的下载链接
    fz_array = []
    # 记录pieces_hash与info_hash的对应关系
    info_hash_topieces = {}
    # 记录pieces_hash与种子文件名的对应关系
    torrent_name_topieces = {}
    logger.info('辅种脚本启动')
    # 读取种子文件夹中的所有种子文件
    for file_name in os.listdir(folder_path):
        if file_name.endswith('.torrent'):
            file_path = os.path.join(folder_path, file_name)
            try:
                with open(file_path, 'rb') as f:
                    torrent_data = f.read()
                    torrent = bencodepy.decode(torrent_data)
                    info = torrent[b'info']
                    pieces = info[b'pieces']
                    info_sha1 = hashlib.sha1(bencodepy.encode(info)).hexdigest()
                    pieces_sha1 = hashlib.sha1(pieces).hexdigest()
                    pieces_hash_list.append(pieces_sha1)
                    info_hash_topieces[pieces_sha1] = info_sha1
                    torrent_name_topieces[pieces_sha1] = file_name
            except:
                continue
    # 去除已记录在cache中种子
    pieces_hash_list = remove_cached_values(pieces_hash_list)
    logger.info("当前种子库：%d 个种子", len(pieces_hash_list))
    sites = getSiteInfo()
    # 只查询配置了passkey的站点
    for site in filter(lambda x: x['passkey'], sites):
        pieces_hash_groups = [pieces_hash_list[i:i+100] for i in range(0, len(pieces_hash_list), 100)]
        for group_list in pieces_hash_groups:
            headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "User-Agent": "Reseed-Puppy"
            }
            data = {
                "passkey": site['passkey'],
                "pieces_hash": group_list
            }
            url = site['apiUrl']
            try:
                response = requests.post(url, headers=headers, json=data, timeout=10)
                response.raise_for_status()
            except requests.exceptions.RequestException as e:
                logger.warning('站点请求失败：%s - %s', site['siteName'], e)
                continue
            response_json = response.json()
            # 判断返回的数据是否为字典，某些站点没有查询到结果时返回的是数组
            if isinstance(response_json.get('data'), dict):
                for value in response_json['data']:
                    if(value == "passkey" or value=="pieces_hash"):
                        continue
                    try:
                        # 有查询到结果的种子通过pieces_hash来获取info_hash，再通过info_hash来获取种子的保存路径和状态
                        torrent_info = c.get_torrent(torrent_id=info_hash_topieces[value])
                        # 获取种子的保存路径和状态
                        save_path = torrent_info.download_dir
                        state = torrent_info.status
                        # 将其他站点相同的种子添加到下载器中(传递拼接好的下载链接和获取到的保存目录)
                        if( state != "downloading"):
                            if c.add_torrent(torrent=f"{site['siteUrl']}download.php?id={response_json['data'][value]}&passkey={site['passkey']}", download_dir=save_path,paused=isaction):
                                logger.info("种子pieces_info:%s", value)
                                logger.info("%s ：正在添加到下载器中",
                                            torrent_name_topieces[value])
                                add_seed_to_cache(value)
                                fz_array.append(f"{site['siteUrl']}download.php?id={response_json['data'][value]}&passkey={site['passkey']}")
                    except:
                        logger.info(f"种子{info_hash_topieces[value]}在下载器中没有找到")
    logger.info("可辅种数：%d 个种子", len(fz_array))
    logger.info(fz_array)
    logger.info("辅种结束")
    return len(fz_array)