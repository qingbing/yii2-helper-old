-- ----------------------------
-- 用途
--   操作日志：该日志主要用于程序员在重要的地方手动打一些操作的日志，主要用于问题排查或重要记录
--
-- 实现逻辑
-- 1. 通过 \YiiHelper\features\operateLog\tools\OperateLog::getInstance()->add() 将日志数据写入 pub_operate_log 表
-- 2. 界面查看操作日志情况
-- ----------------------------

-- ----------------------------
--  Table structure for `{{%operate_logs}}`
-- ----------------------------
CREATE TABLE `{{%operate_logs}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '操作类型-用字符串描述',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字，用于后期筛选',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '操作消息',
  `data` json COMMENT '操作的具体内容',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_systemAlias` (`system_alias`),
  KEY `idx_type` (`type`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作日志表';