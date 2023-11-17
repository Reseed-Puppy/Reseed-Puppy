# 详细项目说明在wiki页面，[请点击此处跳转](https://github.com/Reseed-Puppy/Reseed-Puppy/wiki/%E5%AE%89%E8%A3%85%E6%8C%87%E5%BC%95)
# 内置辅种模式支持站点
#### 红叶、憨憨、猪猪、ultrahd、织梦、hdtime、月月、ptlsp、icc、农场、你堡、肉丝、kufei、咖啡、1ptba、东樱、oshen、明教、2xfree、阿童木、3wmg、象站、聆音、okpt
# Jackett辅种模式支持站点
#### 只要是Jackett中支持的站点都支持
镜像包含了以下环境：

- PHP 7.4.33

容器使用Host模式，否则会请求失败

功能正常使用，需要映射 qBittorrent 或 Transmission 的种子目录，如果需要持久化储存数据，需要将数据库目录映射到宿主机：

- /path/to/qBittorrent/config/qBittorrent/BT_backup:/qb
- /path/to/Transmission/config/torrents:/tr
- /path/to/database:/reseed-puppy-php/database

# 编译安装

建议使用 docker 方式安装，编译安装请自行阅读项目代码并部署。

# docker 安装

```bash
docker run -d \
  --name=reseed-puppy \
  -v /path/to/database:/reseed-puppy-php/database \（这是映射数据库目录）
  -v /path/to/qbtorrents:/qbtorrents \(映射一个qb种子目录，如果有的话)
  -v /path/to/trtorrents:/trtorrents \(映射一个tr种子目录，如果有的话)
  --network host \（必须要使用host模式，不然很多站点请求会失败）
  --restart unless-stopped \
  szzhoubanxian/reseed-puppy:latest
```

# docker-compose

```yaml
---
version: "3"
services:
  reseed-puppy:
    image: szzhoubanxian/reseed-puppy:latest
    container_name: reseed-puppy
    volumes:
      - /path/to/database:/reseed-puppy-php/database
      - /path/to/torrents:/torrents
    network_mode: host
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
