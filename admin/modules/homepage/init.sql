# 首页一级栏目
CREATE TABLE `pf_homepage_column`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT '栏目名',
  `status` TINYINT UNSIGNED DEFAULT 1 COMMENT '状态 0: 禁用　1: 启用',
  PRIMARY KEY (`id`)
)ENGINE INNODB CHARSET utf8mb4;

# 首页分类
CREATE TABLE `pf_homepage_column_item`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT '栏目名',
  `img` VARCHAR(100) NOT NULL COMMENT '图片',
  `column_id` INT UNSIGNED DEFAULT 0 COMMENT '一级栏目ID',
  `status` TINYINT UNSIGNED DEFAULT 1 COMMENT '状态 0: 禁用　1: 启用',
  PRIMARY KEY (`id`),
  INDEX (`column_id`)
)ENGINE INNODB CHARSET utf8mb4;

# 首页控制品牌
CREATE TABLE `pf_homepage_column_brand`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `brand_id` INT UNSIGNED NOT NULL COMMENT '品牌ID',
  `img` VARCHAR(100) NOT NULL COMMENT '品牌logo',
  `column_id` INT UNSIGNED NOT NULL COMMENT '一级栏目ID',
  `status` TINYINT UNSIGNED DEFAULT 1 COMMENT '状态 0: 禁用　1: 启用',
  PRIMARY KEY (`id`),
  INDEX (`column_id`)
)ENGINE INNODB CHARSET utf8mb4;

# 公告
CREATE TABLE `pf_homepage_post`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL COMMENT '标题',
  `img` VARCHAR(100) NOT NULL COMMENT '图片',
  `type` TINYINT UNSIGNED COMMENT '类型 1: 公告 2:链接',
  `url` VARCHAR(200) COMMENT '链接地址',
  `content` TEXT COMMENT '内容',
  `author` VARCHAR(100) NOT NULL COMMENT '发布人',
  `admin_user_id` INT UNSIGNED COMMENT '发布人ID',
  PRIMARY KEY (`id`),
  INDEX (`admin_user_id`)
)ENGINE INNODB CHARSET utf8mb4;

# wep轮播
CREATE TABLE `pf_homepage_wap`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `img_url` VARCHAR(128) NOT NULL COMMENT '图片链接',
  `file_name` VARCHAR(64) NOT NULL COMMENT '图片地址',
  `product_url` VARCHAR(128) NOT NULL COMMENT '图片地址',
  `sort` TINYINT UNSIGNED DEFAULT 99 COMMENT '序号',
  `is_del` TINYINT UNSIGNED DEFAULT 0 COMMENT '是否被删除 0: 未删除 1: 已删除',
  PRIMARY KEY (`id`)
);

# 热搜关键词
CREATE TABLE `pf_homepage_keywords`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(16) NOT NULL COMMENT '词',
  `sort` TINYINT UNSIGNED DEFAULT 99 COMMENT '排序',
  PRIMARY KEY (`id`)
);

# 品牌
CREATE TABLE `pf_homepage_brand`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL COMMENT '品牌名',
  `brand_id` INT UNSIGNED NOT NULL COMMENT '品牌ID',
  `company_name` VARCHAR(64) NOT NULL COMMENT '供应商名',
  `logo_name` VARCHAR(128) NOT NULL COMMENT 'logo',
  `sort` TINYINT UNSIGNED DEFAULT 99,
  PRIMARY KEY (`id`),
  INDEX (`brand_id`)
)