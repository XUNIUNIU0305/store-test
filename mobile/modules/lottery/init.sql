# 抽奖计划
DROP TABLE IF EXISTS `pf_lottery_plan`;
create TABLE `pf_lottery_plan`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(100) COMMENT '计划名',
  `start_date` DATETIME COMMENT '开始时间',
  `end_date` DATETIME COMMENT '结束时间',
  `money_limit` DECIMAL(12,2) UNSIGNED COMMENT '策略门槛',
  `status` TINYINT UNSIGNED DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`)
)ENGINE InnoDB CHARSET utf8mb4;

# 计划品牌/商品
DROP TABLE IF EXISTS `pf_lottery_plan_product`;
CREATE TABLE `pf_lottery_plan_product`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT '品牌或商品名',
  `brand_id` INT UNSIGNED COMMENT '品牌ID',
  `product_id` INT UNSIGNED COMMENT '商品ID',
  `plan_id` INT UNSIGNED NOT NULL COMMENT '计划ID',
  PRIMARY KEY (`id`)
)ENGINE InnoDB CHARSET utf8mb4;

# 用户抽奖机会
DROP TABLE IF EXISTS `pf_lottery_chance`;
CREATE TABLE `pf_lottery_chance`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `custom_user_id` INT UNSIGNED NOT NULL COMMENT '用户ID',
  `account` VARCHAR(64) NOT NULL COMMENT '用户账号',
  `total_fee` DECIMAL(12,2) UNSIGNED NOT NULL COMMENT '总消费',
  `plan_total_fee` DECIMAL(12,2) UNSIGNED COMMENT '当前计划总消费',
  `plan_id` INT UNSIGNED NOT NULL COMMENT '计划ID',
  `chance` INT UNSIGNED DEFAULT 0 COMMENT '机会数量',
  `created` DATETIME COMMENT '获取时间',
  PRIMARY KEY (`id`)
)ENGINE InnoDB CHARSET utf8mb4;

# 抽奖机会详情
DROP TABLE IF EXISTS `pf_lottery_chance_item`;
CREATE TABLE `pf_lottery_chance_item`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `chance_id` INT UNSIGNED NOT NULL COMMENT '机会ID',
  `status` TINYINT UNSIGNED DEFAULT 0 COMMENT '状态',
  `result` TINYINT UNSIGNED DEFAULT 0 COMMENT '抽奖结果',
  `open_date` DATETIME,
  PRIMARY KEY (`id`)
)ENGINE InnoDB CHARSET utf8mb4;

# 奖品
DROP TABLE IF EXISTS `pf_lottery_prize`;
CREATE TABLE `pf_lottery_prize` (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `plan_id` INT UNSIGNED NOT NULL COMMENT '计划ID',
  `name` VARCHAR(100) NOT NULL COMMENT '奖品名',
  `price` DECIMAL(12,2) UNSIGNED COMMENT '奖品价值',
  `num` INT UNSIGNED COMMENT '数量',
  `total` INT UNSIGNED COMMENT '总额',
  `probability` DECIMAL(10,8) COMMENT '概率',
  `prize_limit` DECIMAL(10,2) COMMENT '消费限制',
  PRIMARY KEY (`id`),
  INDEX (`plan_id`)
)ENGINE InnoDB CHARSET utf8mb4;

# 中奖奖品
DROP TABLE IF EXISTS `pf_lottery_chance_prize`;
CREATE TABLE `pf_lottery_chance_prize`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `custom_user_id` INT UNSIGNED NOT NULL COMMENT '中奖用户ID',
  `chance_id` INT UNSIGNED NOT NULL COMMENT '机会ID',
  `prize_id` INT UNSIGNED NOT NULL COMMENT '奖品ID',
  `type` TINYINT UNSIGNED DEFAULT 10 COMMENT '奖品类型',
  `status` TINYINT UNSIGNED DEFAULT 0 COMMENT '奖品状态',
  `name` VARCHAR(100) NOT NULL COMMENT '奖品名',
  `created` DATETIME COMMENT '中奖时间',
  PRIMARY KEY (`id`)
)ENGINE InnoDB CHARSET utf8mb4;


# 发奖
CREATE TABLE `pf_admin_pay_prize`(
  `id` INT UNSIGNED AUTO_INCREMENT,
  `admin_pay_log_id` INT UNSIGNED NOT NULL COMMENT 'admin支付记录ID',
  `prize_id` INT UNSIGNED NOT NULL COMMENT '奖品ID',
  PRIMARY KEY (`id`),
  INDEX (`admin_pay_log_id`),
  INDEX (`prize_id`)
)ENGINE InnoDB CHARSET utf8mb4;