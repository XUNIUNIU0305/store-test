CREATE TABLE `pf_supply_user_express` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `express_id` int(10) UNSIGNED NOT NULL COMMENT '快递ID',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;