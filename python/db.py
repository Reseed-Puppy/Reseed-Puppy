import pymysql
import threading
# 创建一个 Lock 对象
lock = threading.Lock()
# 连接到数据库
connection = pymysql.connect(
    host='127.0.0.1',
    user='root',
    password='123456',
    db='reseed_puppy'
)

def getDownloadInfo(download_id):
    with connection.cursor(pymysql.cursors.DictCursor) as cursor:
            lock.acquire()
            sql = "SELECT * FROM download_config WHERE id = %s"
            cursor.execute(sql, (download_id,))
            result = cursor.fetchone() 
            lock.release() 
            return result
            

def getSiteInfo():
    with connection.cursor(pymysql.cursors.DictCursor) as cursor:
            lock.acquire()
            sql = "SELECT * FROM site_config WHERE status = 1"
            cursor.execute(sql)
            result = cursor.fetchall() 
            lock.release() 
            return result
            