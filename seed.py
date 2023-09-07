from cache import add_seed_to_cache,remove_cached_values
from config.site_config import sites
from config.qb_config import qb
from log import writeLog
import qbittorrentapi
import bencodepy
import requests
import hashlib
import json
import os
logger = writeLog('my_logger', '/reseed-puppy/python/log/reseed.log')
# 辅种主函数
def seed():
  # 配置qbittorrentapi的连接参数以及尝试连接
  if qb['url'] == None or qb['url'] == '':
      logger.error('请先配置qbittorrentapi的连接参数')
      return 0
  else:
    conn_info = dict(
      host=qb['url'],
      port=qb['port'],
      username=qb['username'],
      password=qb['password'],
    )
    qbt_client = qbittorrentapi.Client(**conn_info)
    try:
      qbt_client.auth_log_in()
    except qbittorrentapi.LoginFailed as e:
      return 0
    # 配置种子文件夹路径
    folder_path = 'torrents'
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
                    # 有查询到结果的种子通过pieces_hash来获取info_hash，再通过info_hash来获取种子的保存路径和状态
                    torrent_info = qbt_client.torrents_info(torrent_hashes=info_hash_topieces[value])
                    # 判断种子是否已经在下载器中
                    if (torrent_info):
                        # 获取种子的保存路径和状态
                        save_path = torrent_info[0]['save_path']
                        state = torrent_info[0]['state']
                        # 将其他站点相同的种子添加到下载器中(传递拼接好的下载链接和获取到的保存目录)
                        if qbt_client.torrents_add(urls=f"{site['siteUrl']}download.php?id={response_json['data'][value]}&passkey={site['passkey']}", save_path=save_path) == "Ok." and state != "downloading":
                            logger.info("种子pieces_info:%s", value)
                            logger.info("%s ：正在添加到下载器中",
                                        torrent_name_topieces[value])
                            add_seed_to_cache(value)
                            fz_array.append(f"{site['siteUrl']}download.php?id={response_json['data'][value]}&passkey={site['passkey']}")

    logger.info("可辅种数：%d 个种子", len(fz_array))
    logger.info(fz_array)
    logger.info("辅种结束")
