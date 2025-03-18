ALTER TABLE `jjjfood_order`
ADD COLUMN `wx_delivery_status`  tinyint(3) UNSIGNED NOT NULL DEFAULT 10 COMMENT '微信发货状态(10未发货 20已发货)' AFTER `trade_no`;
INSERT INTO `jjjfood_shop_access` VALUES ('1714385488', '微信小程序发货', '/store/order/wxDelivery', '1626686852', '1', '', '', '1', '0', '', '1', '0', '', '1', null, '1714385488', '1714385488');
INSERT INTO `jjjfood_shop_access` VALUES ('1714385566', '微信小程序发货', '/takeout/order/wxDelivery', '1626688646', '1', '', '', '1', '0', '', '1', '0', '', '1', null, '1714385566', '1714385566');