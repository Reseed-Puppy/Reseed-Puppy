from sanic.response import json, text
from log import writeLog
from db import getDownloadInfo
from sanic import Sanic
from qbseed import qbseed, connect_to_qbittorrent
from trseed import trseed, connect_to_transmission

logger = writeLog("index_logger", "/reseed-puppy/python/log/reseed.log")
app = Sanic(__name__)


@app.get("/")
async def index(request):
    download_id = request.args.get("download_id")
    result = getDownloadInfo(download_id)
    if result["type"] == 1:
        msg = qbseed(result)
    elif result["type"] == 2:
        msg = trseed(result)
    else:
        logger.warning("下载器ID出错")
        msg = "下载器ID出错"
        return msg
    return text(f"辅种脚本执行完毕,本次成功辅种{msg}个")


@app.get("/delreseed")
async def delreseed(request):
    with open('cache/seed.txt', 'w') as file:
        file.truncate(0)
    return json({"code": 200, "msg": "辅种缓存清理成功"})


@app.post("/qbconnect")
async def qbconnect(request):
    required_fields = ["password", "url", "port", "username"]
    qb = {field: request.form.get(field) for field in required_fields}
    try:
        msg = connect_to_qbittorrent(qb)
        if(msg == 0):
            return json({"code": 200, "msg": "qbittorrent连接失败"})
        else:
            return json({"code": 200, "msg": "qbittorrent连接成功"})
    except Exception as e:
        logger.error(f"连接到qbittorrent时出错: {str(e)}")
        return json({"code": 200, "msg": str(e)})


@app.post("/trconnect")
async def trconnect(request):
    required_fields = ["password", "url", "port", "username"]
    tr = {field: request.form.get(field) for field in required_fields}
    try:
        msg = connect_to_transmission(tr)
        if(msg == 0):
            return json({"code": 200, "msg": "transmission连接失败"})
        else:
            return json({"code": 200, "msg": "transmission连接成功"})
    except Exception as e:
        logger.error(f"连接到transmission时出错: {str(e)}")
        return json({"code": 200, "msg": str(e)})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
