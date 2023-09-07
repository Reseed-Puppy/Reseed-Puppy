/*
 Navicat Premium Data Transfer

 Source Server         : reseed_puppy
 Source Server Type    : MySQL
 Source Server Version : 101103
 Source Host           : zxfjcyx.iok.la:3333
 Source Schema         : reseed_puppy

 Target Server Type    : MySQL
 Target Server Version : 101103
 File Encoding         : 65001

 Date: 28/08/2023 14:56:31
*/
USE reseed_puppy;
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for download_config
-- ----------------------------
DROP TABLE IF EXISTS `download_config`;
CREATE TABLE `download_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '下载器名称',
  `type` int(11) NOT NULL COMMENT '下载器类型 {select} (1:qb, 2:tr)',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '下载器地址',
  `port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '下载器端口',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '下载器用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '下载器密码',
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '下载器映射目录',
  `skiphash` int(11) NOT NULL COMMENT '跳过hash校验 {select} (1:否, 2:是)',
  `isaction` int(11) NOT NULL COMMENT '自动开始 {select} (1:否, 2:是)',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of download_config
-- ----------------------------

-- ----------------------------
-- Table structure for site_config
-- ----------------------------
DROP TABLE IF EXISTS `site_config`;
CREATE TABLE `site_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '站点名称',
  `siteUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '站点地址',
  `apiUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '站点接口地址',
  `passkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '站点passkey',
  `status` int(11) NOT NULL COMMENT '状态 {switch} (0:禁用,1:启用)',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间\r\n',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of site_config
-- ----------------------------
INSERT INTO `site_config` VALUES (1, '红叶', 'https://leaves.red/', 'https://leaves.red/api/pieces-hash', '改成你的', 0, 1692986445, 1692986673, NULL);
INSERT INTO `site_config` VALUES (2, '猪猪', 'https://piggo.me/', 'https://api.piggo.me/api/pieces-hash', '改成你的', 0, 1693068496, 1693068496, NULL);
INSERT INTO `site_config` VALUES (3, 'ultrahd', 'https://ultrahd.net/', 'https://ultrahd.net/api/pieces-hash', '改成你的', 0, 1693068526, 1693068526, NULL);
INSERT INTO `site_config` VALUES (4, 'zmpt(织梦)', 'https://zmpt.cc/', 'https://zmpt.cc/api/pieces-hash', '改成你的', 0, 1693068549, 1693068549, NULL);
INSERT INTO `site_config` VALUES (5, 'hdtime', 'https://hdtime.org/', 'https://hdtime.org/api/pieces-hash', '改成你的', 0, 1693068571, 1693068571, NULL);
INSERT INTO `site_config` VALUES (6, '月月', 'https://pt.keepfrds.com', 'https://pt.keepfrds.com/api/torrents/pieces-hash', '改成你的', 0, 1693068600, 1693068600, NULL);
INSERT INTO `site_config` VALUES (7, 'ptlsp', 'https://www.ptlsp.com/', 'https://www.ptlsp.com/api/pieces-hash', '改成你的', 0, 1693068651, 1693068651, NULL);
INSERT INTO `site_config` VALUES (8, '憨憨', 'https://hhanclub.top/', 'https://hhanclub.top/npapi/pieces-hash', '改成你的', 0, 1693068734, 1693068734, NULL);
INSERT INTO `site_config` VALUES (9, '大青虫', 'https://cyanbug.net/', 'https://cyanbug.net/api/pieces-hash', '改成你的', 0, 1693068734, 1693068734, NULL);

-- ----------------------------
-- Table structure for system_admin
-- ----------------------------
DROP TABLE IF EXISTS `system_admin`;
CREATE TABLE `system_admin`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auth_ids` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '角色权限ID',
  `head_img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '头像',
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '用户登录名',
  `password` char(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '用户登录密码',
  `phone` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '联系手机号',
  `remark` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '备注说明',
  `login_num` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '登录次数',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用,)',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE,
  INDEX `phone`(`phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统用户表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_admin
-- ----------------------------
INSERT INTO `system_admin` VALUES (1, NULL, '/static/admin/images/head.jpg', 'admin', 'a33b679d5581a8692988ec9f92ad2d6a2259eaa7', NULL, '', 5, 0, 1, 1692981131, 1693096828, NULL);

-- ----------------------------
-- Table structure for system_auth
-- ----------------------------
DROP TABLE IF EXISTS `system_auth`;
CREATE TABLE `system_auth`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '权限名称',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '状态(1:禁用,2:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '备注说明',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统权限表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_auth
-- ----------------------------
INSERT INTO `system_auth` VALUES (1, '管理员', 1, 1, '测试管理员', 1588921753, 1589614331, NULL);
INSERT INTO `system_auth` VALUES (6, '游客权限', 0, 1, '', 1588227513, 1589591751, 1589591751);

-- ----------------------------
-- Table structure for system_auth_node
-- ----------------------------
DROP TABLE IF EXISTS `system_auth_node`;
CREATE TABLE `system_auth_node`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auth_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '角色ID',
  `node_id` bigint(20) NULL DEFAULT NULL COMMENT '节点ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_auth_auth`(`auth_id`) USING BTREE,
  INDEX `index_system_auth_node`(`node_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '角色与节点关系表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_auth_node
-- ----------------------------
INSERT INTO `system_auth_node` VALUES (1, 6, 1);
INSERT INTO `system_auth_node` VALUES (2, 6, 2);
INSERT INTO `system_auth_node` VALUES (3, 6, 9);
INSERT INTO `system_auth_node` VALUES (4, 6, 12);
INSERT INTO `system_auth_node` VALUES (5, 6, 18);
INSERT INTO `system_auth_node` VALUES (6, 6, 19);
INSERT INTO `system_auth_node` VALUES (7, 6, 21);
INSERT INTO `system_auth_node` VALUES (8, 6, 22);
INSERT INTO `system_auth_node` VALUES (9, 6, 29);
INSERT INTO `system_auth_node` VALUES (10, 6, 30);
INSERT INTO `system_auth_node` VALUES (11, 6, 38);
INSERT INTO `system_auth_node` VALUES (12, 6, 39);
INSERT INTO `system_auth_node` VALUES (13, 6, 45);
INSERT INTO `system_auth_node` VALUES (14, 6, 46);
INSERT INTO `system_auth_node` VALUES (15, 6, 52);
INSERT INTO `system_auth_node` VALUES (16, 6, 53);

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '分组',
  `value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '变量值',
  `remark` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '备注信息',
  `sort` int(10) NULL DEFAULT 0,
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `group`(`group`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统配置表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES (41, 'alisms_access_key_id', 'sms', '填你的', '阿里大于公钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (42, 'alisms_access_key_secret', 'sms', '填你的', '阿里大鱼私钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (55, 'upload_type', 'upload', 'local', '当前上传方式 （local,alioss,qnoss,txoss）', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (56, 'upload_allow_ext', 'upload', 'doc,gif,ico,icon,jpg,mp3,mp4,p12,pem,png,rar,jpeg', '允许上传的文件类型', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (57, 'upload_allow_size', 'upload', '1024000', '允许上传的大小', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (58, 'upload_allow_mime', 'upload', 'image/gif,image/jpeg,video/x-msvideo,text/plain,image/png', '允许上传的文件mime', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (59, 'upload_allow_type', 'upload', 'local,alioss,qnoss,txcos', '可用的上传文件方式', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (60, 'alioss_access_key_id', 'upload', '填你的', '阿里云oss公钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (61, 'alioss_access_key_secret', 'upload', '填你的', '阿里云oss私钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (62, 'alioss_endpoint', 'upload', '填你的', '阿里云oss数据中心', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (63, 'alioss_bucket', 'upload', '填你的', '阿里云oss空间名称', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (64, 'alioss_domain', 'upload', '填你的', '阿里云oss访问域名', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (65, 'logo_title', 'site', 'Reseed-Puppy', 'LOGO标题', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (66, 'logo_image', 'site', '/favicon.ico', 'logo图片', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (68, 'site_name', 'site', 'Reseed-Puppy', '站点名称', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (69, 'site_ico', 'site', '填你的', '浏览器图标', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (70, 'site_copyright', 'site', 'Puppy', '版权信息', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (71, 'site_beian', 'site', 'Reseed-', '备案信息', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (72, 'site_version', 'site', '2.0.0', '版本信息', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (75, 'sms_type', 'sms', 'alisms', '短信类型', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (76, 'miniapp_appid', 'wechat', '填你的', '小程序公钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (77, 'miniapp_appsecret', 'wechat', '填你的', '小程序私钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (78, 'web_appid', 'wechat', '填你的', '公众号公钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (79, 'web_appsecret', 'wechat', '填你的', '公众号私钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (80, 'txcos_secret_id', 'upload', '填你的', '腾讯云cos密钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (81, 'txcos_secret_key', 'upload', '填你的', '腾讯云cos私钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (82, 'txcos_region', 'upload', '填你的', '存储桶地域', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (83, 'tecos_bucket', 'upload', '填你的', '存储桶名称', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (84, 'qnoss_access_key', 'upload', '填你的', '访问密钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (85, 'qnoss_secret_key', 'upload', '填你的', '安全密钥', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (86, 'qnoss_bucket', 'upload', '填你的', '存储空间', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (87, 'qnoss_domain', 'upload', '填你的', '访问域名', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (88, 'upload_allow_image_size', 'upload', '1024000', '允许上传的图片大小', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (89, 'upload_allow_video_size', 'upload', '1024000', '允许上传的视频大小', 0, NULL, NULL);
INSERT INTO `system_config` VALUES (90, 'upload_allow_audio_size', 'upload', '1024000', '允许上传的音频大小', 0, NULL, NULL);

-- ----------------------------
-- Table structure for system_crontab
-- ----------------------------
DROP TABLE IF EXISTS `system_crontab`;
CREATE TABLE `system_crontab`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务标题',
  `download_id` int(11) NULL DEFAULT NULL COMMENT '辅种下载器',
  `type` tinyint(4) NULL DEFAULT 0 COMMENT '任务类型[0请求url,1执行sql,2执行shell]',
  `frequency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务频率',
  `running_times` int(11) NULL DEFAULT 0 COMMENT '已运行次数',
  `last_running_time` int(11) NULL DEFAULT 0 COMMENT '最近运行时间',
  `status` tinyint(4) NULL DEFAULT 0 COMMENT '状态 {switch} (0:禁用,1:启用)',
  `create_time` int(11) NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT 0 COMMENT '更新时间',
  `sort` int(1) NULL DEFAULT NULL COMMENT '排序',
  `shell` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务脚本',
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_crontab
-- ----------------------------

-- ----------------------------
-- Table structure for system_crontab_flow_202308
-- ----------------------------
DROP TABLE IF EXISTS `system_crontab_flow_202308`;
CREATE TABLE `system_crontab_flow_202308`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` int(60) NOT NULL COMMENT '任务id',
  `command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '执行命令',
  `output` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '执行输出',
  `return_var` tinyint(4) NOT NULL COMMENT '执行返回状态[0成功; 1失败]',
  `running_time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '执行所用时间',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sid`(`sid`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6717 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务流水表202308' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_crontab_flow_202308
-- ----------------------------

-- ----------------------------
-- Table structure for system_crontab_lock
-- ----------------------------
DROP TABLE IF EXISTS `system_crontab_lock`;
CREATE TABLE `system_crontab_lock`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` int(60) NOT NULL COMMENT '任务id',
  `is_lock` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否锁定(0:否,1是)',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sid`(`sid`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务锁表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_crontab_lock
-- ----------------------------

-- ----------------------------
-- Table structure for system_exception_log
-- ----------------------------
DROP TABLE IF EXISTS `system_exception_log`;
CREATE TABLE `system_exception_log`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '异常消息内容',
  `code` int(11) NULL DEFAULT NULL COMMENT '异常代码',
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建异常时的程序文件名称',
  `line` int(11) NULL DEFAULT NULL COMMENT '创建的异常所在文件中的行号',
  `trace` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '异常追踪信息',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求url',
  `method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '请求方法',
  `param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '请求参数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '请求ip',
  `header` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '请求头',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '异常日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_exception_log
-- ----------------------------

-- ----------------------------
-- Table structure for system_log_202308
-- ----------------------------
DROP TABLE IF EXISTS `system_log_202308`;
CREATE TABLE `system_log_202308`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '管理员ID',
  `url` varchar(1500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '操作页面',
  `method` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '请求方法',
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '日志标题',
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT 'User-Agent',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 754 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '后台操作日志表 - 202308' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_log_202308
-- ----------------------------

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `href` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '链接',
  `params` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) NULL DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `href`(`href`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 258 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统菜单表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES (227, 99999999, '后台首页', 'fa fa-home', 'index/welcome', '', '_self', 0, 1, NULL, NULL, 1573120497, NULL);
INSERT INTO `system_menu` VALUES (228, 0, '系统管理', 'fa fa-cog', '', '', '_self', 0, 1, '', NULL, 1588999529, NULL);
INSERT INTO `system_menu` VALUES (234, 228, '菜单管理', 'fa fa-tree', 'system.menu/index', '', '_self', 10, 1, '', NULL, 1588228555, NULL);
INSERT INTO `system_menu` VALUES (244, 228, '管理员管理', 'fa fa-user', 'system.admin/index', '', '_self', 12, 0, '', 1573185011, 1692982164, NULL);
INSERT INTO `system_menu` VALUES (245, 228, '角色管理', 'fa fa-bitbucket-square', 'system.auth/index', '', '_self', 11, 0, '', 1573435877, 1692982163, NULL);
INSERT INTO `system_menu` VALUES (246, 228, '节点管理', 'fa fa-list', 'system.node/index', '', '_self', 9, 0, '', 1573435919, 1692984888, NULL);
INSERT INTO `system_menu` VALUES (247, 228, '配置管理', 'fa fa-asterisk', 'system.config/index', '', '_self', 8, 0, '', 1573457448, 1692982156, NULL);
INSERT INTO `system_menu` VALUES (248, 228, '上传管理', 'fa fa-arrow-up', 'system.uploadfile/index', '', '_self', 0, 0, '', 1573542953, 1692982059, NULL);
INSERT INTO `system_menu` VALUES (252, 228, '快捷入口', 'fa fa-list', 'system.quick/index', '', '_self', 0, 0, '', 1589623683, 1692982047, NULL);
INSERT INTO `system_menu` VALUES (253, 228, '日志管理', 'fa fa-connectdevelop', 'system.log/index', '', '_self', 0, 0, '', 1589623684, 1692982032, NULL);
INSERT INTO `system_menu` VALUES (254, 228, '定时任务', 'fa fa-clock-o', 'system.crontab/index', '', '_self', 90, 1, '', 1642576980, 1692985137, NULL);
INSERT INTO `system_menu` VALUES (255, 228, '异常日志', 'fa fa-clipboard', 'system.exception_log/index', '', '_self', 0, 0, '', 1680316382, 1692985124, NULL);
INSERT INTO `system_menu` VALUES (256, 228, '下载器配置', 'fa fa-download', 'download.config/index', '', '_self', 99, 1, '', 1692984939, 1692984939, NULL);
INSERT INTO `system_menu` VALUES (257, 228, '站点配置', 'fa fa-sitemap', 'site.config/index', '', '_self', 100, 1, '', 1692984972, 1692984972, NULL);

-- ----------------------------
-- Table structure for system_node
-- ----------------------------
DROP TABLE IF EXISTS `system_node`;
CREATE TABLE `system_node`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `node` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '节点代码',
  `title` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '节点标题',
  `type` tinyint(1) NULL DEFAULT 3 COMMENT '节点类型（1：控制器，2：节点）',
  `is_auth` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否启动RBAC权限控制',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `node`(`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 94 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统节点表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_node
-- ----------------------------
INSERT INTO `system_node` VALUES (1, 'system.admin', '管理员管理', 1, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (2, 'system.admin/index', '列表', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (3, 'system.admin/add', '添加', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (4, 'system.admin/edit', '编辑', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (5, 'system.admin/password', '编辑', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (6, 'system.admin/delete', '删除', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (7, 'system.admin/modify', '属性修改', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (8, 'system.admin/export', '导出', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (9, 'system.auth', '角色权限管理', 1, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (10, 'system.auth/authorize', '授权', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (11, 'system.auth/saveAuthorize', '授权保存', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (12, 'system.auth/index', '列表', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (13, 'system.auth/add', '添加', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (14, 'system.auth/edit', '编辑', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (15, 'system.auth/delete', '删除', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (16, 'system.auth/export', '导出', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (17, 'system.auth/modify', '属性修改', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (18, 'system.config', '系统配置管理', 1, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (19, 'system.config/index', '列表', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (20, 'system.config/save', '保存', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (21, 'system.menu', '菜单管理', 1, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (22, 'system.menu/index', '列表', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (23, 'system.menu/add', '添加', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (24, 'system.menu/edit', '编辑', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (25, 'system.menu/delete', '删除', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (26, 'system.menu/modify', '属性修改', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (27, 'system.menu/getMenuTips', '添加菜单提示', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (28, 'system.menu/export', '导出', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (29, 'system.node', '系统节点管理', 1, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (30, 'system.node/index', '列表', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (31, 'system.node/refreshNode', '系统节点更新', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (32, 'system.node/clearNode', '清除失效节点', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (33, 'system.node/add', '添加', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (34, 'system.node/edit', '编辑', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (35, 'system.node/delete', '删除', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (36, 'system.node/export', '导出', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (37, 'system.node/modify', '属性修改', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (38, 'system.uploadfile', '上传文件管理', 1, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (39, 'system.uploadfile/index', '列表', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (40, 'system.uploadfile/add', '添加', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (41, 'system.uploadfile/edit', '编辑', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (42, 'system.uploadfile/delete', '删除', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (43, 'system.uploadfile/export', '导出', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (44, 'system.uploadfile/modify', '属性修改', 2, 1, 1589580432, 1589580432);
INSERT INTO `system_node` VALUES (60, 'system.quick', '快捷入口管理', 1, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (61, 'system.quick/index', '列表', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (62, 'system.quick/add', '添加', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (63, 'system.quick/edit', '编辑', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (64, 'system.quick/delete', '删除', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (65, 'system.quick/export', '导出', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (66, 'system.quick/modify', '属性修改', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (67, 'system.log', '操作日志管理', 1, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (68, 'system.log/index', '列表', 2, 1, 1589623188, 1589623188);
INSERT INTO `system_node` VALUES (69, 'system.crontab', '定时任务管理', 1, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (70, 'system.crontab/index', '列表', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (71, 'system.crontab/add', '添加', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (72, 'system.crontab/edit', '编辑', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (73, 'system.crontab/modify', '属性修改', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (74, 'system.crontab/delete', '删除', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (75, 'system.crontab/reload', '重启', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (76, 'system.crontab/flow', '日志', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (77, 'system.crontab/ping', '心跳', 2, 1, 1679472487, 1679472487);
INSERT INTO `system_node` VALUES (78, 'system.exception_log', '异常日志管理', 1, 1, 1680830805, 1680830805);
INSERT INTO `system_node` VALUES (79, 'system.exception_log/index', '列表', 2, 1, 1680830805, 1680830805);
INSERT INTO `system_node` VALUES (80, 'site.config', 'site_config', 1, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (81, 'site.config/index', '列表', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (82, 'site.config/add', '添加', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (83, 'site.config/edit', '编辑', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (84, 'site.config/delete', '删除', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (85, 'site.config/export', '导出', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (86, 'site.config/modify', '属性修改', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (87, 'download.config', 'download_config', 1, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (88, 'download.config/index', '列表', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (89, 'download.config/add', '添加', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (90, 'download.config/edit', '编辑', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (91, 'download.config/delete', '删除', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (92, 'download.config/export', '导出', 2, 1, 1692984877, 1692984877);
INSERT INTO `system_node` VALUES (93, 'download.config/modify', '属性修改', 2, 1, 1692984877, 1692984877);

-- ----------------------------
-- Table structure for system_quick
-- ----------------------------
DROP TABLE IF EXISTS `system_quick`;
CREATE TABLE `system_quick`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT '快捷入口名称',
  `icon` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '图标',
  `href` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '快捷链接',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '状态(1:禁用,2:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '备注说明',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '系统快捷入口表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_quick
-- ----------------------------
INSERT INTO `system_quick` VALUES (1, '管理员管理', 'fa fa-user', 'system.admin/index', 0, 1, '', 1589624097, 1589624792, NULL);
INSERT INTO `system_quick` VALUES (2, '角色管理', 'fa fa-bitbucket-square', 'system.auth/index', 0, 1, '', 1589624772, 1589624781, NULL);
INSERT INTO `system_quick` VALUES (3, '菜单管理', 'fa fa-tree', 'system.menu/index', 0, 1, NULL, 1589624097, 1589624792, NULL);
INSERT INTO `system_quick` VALUES (6, '节点管理', 'fa fa-list', 'system.node/index', 0, 1, NULL, 1589624772, 1589624781, NULL);
INSERT INTO `system_quick` VALUES (7, '配置管理', 'fa fa-asterisk', 'system.config/index', 0, 1, NULL, 1589624097, 1589624792, NULL);
INSERT INTO `system_quick` VALUES (8, '上传管理', 'fa fa-arrow-up', 'system.uploadfile/index', 0, 1, NULL, 1589624772, 1589624781, NULL);

-- ----------------------------
-- Table structure for system_uploadfile
-- ----------------------------
DROP TABLE IF EXISTS `system_uploadfile`;
CREATE TABLE `system_uploadfile`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `upload_type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `original_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '文件原名',
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `image_width` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '宽度',
  `image_height` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '高度',
  `image_type` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '图片类型',
  `image_frames` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片帧数',
  `mime_type` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `file_ext` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `sha1` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '创建日期',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `upload_time` int(10) NULL DEFAULT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `upload_type`(`upload_type`) USING BTREE,
  INDEX `original_name`(`original_name`) USING BTREE,
  INDEX `sha1`(`sha1`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 316 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '上传文件表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_uploadfile
-- ----------------------------
INSERT INTO `system_uploadfile` VALUES (286, 'alioss', 'image/jpeg', 'https://lxn-99php.oss-cn-shenzhen.aliyuncs.com/upload/20191111/0a6de1ac058ee134301501899b84ecb1.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', NULL, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (287, 'alioss', 'image/jpeg', 'https://lxn-99php.oss-cn-shenzhen.aliyuncs.com/upload/20191111/46d7384f04a3bed331715e86a4095d15.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', NULL, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (288, 'alioss', 'image/x-icon', 'https://lxn-99php.oss-cn-shenzhen.aliyuncs.com/upload/20191111/7d32671f4c1d1b01b0b28f45205763f9.ico', '', '', '', 0, 'image/x-icon', 0, 'ico', '', NULL, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (289, 'alioss', 'image/jpeg', 'https://lxn-99php.oss-cn-shenzhen.aliyuncs.com/upload/20191111/28cefa547f573a951bcdbbeb1396b06f.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', NULL, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (290, 'alioss', 'image/jpeg', 'https://lxn-99php.oss-cn-shenzhen.aliyuncs.com/upload/20191111/2c412adf1b30c8be3a913e603c7b6e4a.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', NULL, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (291, 'alioss', 'timg (1).jpg', 'http://easyadmin.oss-cn-shenzhen.aliyuncs.com/upload/20191113/ff793ced447febfa9ea2d86f9f88fa8e.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1573612437, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (296, 'txcos', '22243.jpg', 'https://easyadmin-1251997243.cos.ap-guangzhou.myqcloud.com/upload/20191114/2381eaf81208ac188fa994b6f2579953.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1573712153, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (297, 'local', 'timg.jpg', 'http://admin.host/upload/20200423/5055a273cf8e3f393d699d622b74f247.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1587614155, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (298, 'local', 'timg.jpg', 'http://admin.host/upload/20200423/243f4e59f1b929951ef79c5f8be7468a.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1587614269, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (299, 'local', 'head.jpg', 'http://admin.host/upload/20200512/a5ce9883379727324f5686ef61205ce2.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1589255649, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (300, 'local', '896e5b87c9ca70e4.jpg', 'http://admin.host/upload/20200514/577c65f101639f53dbbc9e7aa346f81c.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1589427798, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (301, 'local', '896e5b87c9ca70e4.jpg', 'http://admin.host/upload/20200514/98fc09b0c4ad4d793a6f04bef79a0edc.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1589427840, NULL, NULL);
INSERT INTO `system_uploadfile` VALUES (302, 'local', '18811e7611c8f292.jpg', 'http://admin.host/upload/20200514/e1c6c9ef6a4b98b8f7d95a1a0191a2df.jpg', '', '', '', 0, 'image/jpeg', 0, 'jpg', '', 1589438645, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
