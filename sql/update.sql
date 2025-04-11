/*
ShipSayCMS QQGroup: 249310348
*/

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS  `shipsay_article_search` (
  `searchid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `searchtime` int(11) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `results` smallint(6) NOT NULL,
  `searchsite` varchar(100) NOT NULL,
  PRIMARY KEY (`searchid`),
  KEY `searchtime` (`searchtime`),
  KEY `keywords` (`keywords`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `shipsay_article_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL,
  `chapterid` int(11) NOT NULL,
  `articlename` varchar(100) NOT NULL,
  `chaptername` varchar(200) NOT NULL,
  `repurl` varchar(100) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `reptime` int(11) NOT NULL,
  `content` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chapterid` (`chapterid`),
  KEY `reptime` (`reptime`),
  KEY `articlename` (`articlename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `shipsay_article_langtail` (
  `langid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sourceid` int(11) NOT NULL,
  `langname` varchar(50) NOT NULL DEFAULT '',
  `sourcename` varchar(50) NOT NULL DEFAULT '',
  `uptime` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`langid`),
  KEY `sourceid` (`sourceid`,`langid`),
  UNIQUE KEY `langname`(`langname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
ALTER TABLE `shipsay_article_article`   
ADD COLUMN `lastvote` int(11) unsigned NOT NULL DEFAULT '0',
ADD COLUMN `weekvote` int(11) unsigned NOT NULL DEFAULT '0',
ADD COLUMN `dayvote` int(11) unsigned NOT NULL DEFAULT '0',
ADD COLUMN `monthvote` int(11) unsigned NOT NULL DEFAULT '0',
ADD COLUMN `allvote` int(11) unsigned NOT NULL DEFAULT '0',
ADD COLUMN `goodnum` int(11) unsigned NOT NULL DEFAULT '0';
*/