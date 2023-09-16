# 详细项目说明在wiki页面，[请点击此处跳转](https://github.com/Reseed-Puppy/Reseed-Puppy/wiki/%E5%AE%89%E8%A3%85%E6%8C%87%E5%BC%95)
# 支持站点
#### 红叶、憨憨、猪猪、ultrahd、织梦、hdtime、月月、ptlsp、icc、农场、你堡、肉丝、kufei、咖啡、1ptba、东樱、oshen、明教、2xfree、阿童木、3wmg
镜像包含了以下环境：

- PHP 7.4.33
- Mariadb 10.5.11
- Python 3.9.17

容器使用如下端口，通常情况下只需要映射 1919 端口，其他端口不需要映射到宿主机：

- 1919: Web 管理页面
- 5000: Python 程序后端
- 3306: Mariadb 数据库

功能正常使用，需要映射 qBittorrent 或 Transmission 的种子目录，如果需要持久化储存数据，需要将数据库目录映射到宿主机：

- /path/to/qBittorrent/config/qBittorrent/BT_backup:/qb
- /path/to/Transmission/config/torrents:tr
- /path/to/mysql:/var/lib/mysql

# 编译安装

建议使用 docker 方式安装，编译安装请自行阅读项目代码并部署。

# docker 安装

```bash
docker run -d \
  --name=reseed-puppy \
  -p 1919:1919 \
  -v /path/to/mysql:/var/lib/mysql \
  -v /path/to/torrents:/torrents \
  --restart unless-stopped \
  szzhoubanxian/reseed-puppy:latest
```

# docker-compose

```yaml
---
version: "3"
services:
  heimdall:
    image: szzhoubanxian/reseed-puppy:latest
    container_name: reseed-puppy
    volumes:
      - /path/to/mysql:/var/lib/mysql
      - /path/to/torrents:/torrents
    ports:
      - 1919:1919
    restart: unless-stopped
```

# 群晖安装

在注册表查找 `reseed-puppy` 并拉取镜像，标签建议选择 `latest`，拉取映像可能需要一些时间，取决于网络情况

![DSM_P1](../../wiki/image/DSM_P1.png)

使用 `reseed-puppy` 运行容器

![DSM_P2](../../wiki/image/DSM_P2.png)

配置端口映射和目录映射

![DSM_P3](../../wiki/image/DSM_P3.png)

启动容器即可

![DSM_P4](../../wiki/image/DSM_P4.png)

# 威联通安装

在映像中查找 `reseed-puppy` 并点击部署拉取映像，标签建议选择 `latest`，拉取映像可能需要一些时间，取决于网络情况

![QNAP_P1](../../wiki/image/QNAP_P1.png)

使用 `reseed-puppy` 运行容器

![QNAP_P2](../../wiki/image/QNAP_P2.png)

配置端口映射

![QNAP_P3](../../wiki/image/QNAP_P3.png)

配置目录映射

![QNAP_P4](../../wiki/image/QNAP_P4.png)

启动容器即可

![QNAP_P5](../../wiki/image/QNAP_P5.png)

# Unraid 安装

（待补充）
