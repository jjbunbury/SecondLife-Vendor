DROP TABLE IF EXISTS `#__slvendor_products`;
CREATE TABLE `#__slvendor_products` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `name` varchar(32) NOT NULL,
  `version` varchar(32) NOT NULL,
  `short_desc` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` int(6) NOT NULL,
  `texture_uuid` varchar(36) NOT NULL,
  `object_name` varchar(32) NOT NULL,
  `server_id` int(11) NOT NULL,
  `hits` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`published`)
);
DROP TABLE IF EXISTS `#__slvendor_servers`;
CREATE TABLE `#__slvendor_servers` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `data_channel` varchar(36) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `region` varchar(50) NOT NULL,
  `position` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
);