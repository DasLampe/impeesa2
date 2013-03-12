CREATE TABLE IF NOT EXISTS `impeesa2_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `day` text NOT NULL,
  `youngest` int(2) NOT NULL,
  `oldest` int(3) NOT NULL,
  `begin` time NOT NULL,
  `end` time NOT NULL,
  `logo` text NOT NULL,
  `in_overview` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `impeesa2_groups_leader` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

UPDATE  `impeesa2_config` SET  `config_value` =  '2.0.4a' WHERE  `config_key` =  'version';