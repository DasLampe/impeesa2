ALTER TABLE  `impeesa2_users` ADD  `first_name` TEXT NOT NULL AFTER  `salt` ,
ADD  `name` TEXT NOT NULL AFTER  `first_name`;

CREATE TABLE IF NOT EXISTS `impeesa2_config` (
  `config_key` text NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `impeesa2_config` (`config_key`, `config_value`) VALUES
('adminEmail', 'daslampe@lano-crew.org'),
('unitname', 'Impeesa2 - CMS for Scouts'),
('version', '2.0.1')
('scoutNetId', '7');

ALTER TABLE `impeesa2_config` ADD `description` TEXT NOT NULL AFTER `config_value`;

UPDATE `impeesa2_config` SET
`description` = "Webmaster Email"
WHERE `config_key` = 'adminEmai';

UPDATE `impeesa2_config` SET
`description` = "Name des Stamm"
WHERE `config_key` = 'unitname';

UPDATE `impeesa2_config` SET
`description` = "Impeesa2 Version"
WHERE `config_key` = 'version';

UPDATE `impeesa2_config` SET
`description` = "ScoutNet.de ID"
WHERE `config_key` = 'scoutNetId';