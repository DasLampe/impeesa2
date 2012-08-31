ALTER TABLE  `impeesa2_users` ADD  `first_name` TEXT NOT NULL AFTER  `salt` ,
ADD  `name` TEXT NOT NULL AFTER  `first_name`

CREATE TABLE IF NOT EXISTS `impeesa2_config` (
  `config_key` text NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `impeesa2_config` (`config_key`, `config_value`) VALUES
('adminEmail', 'daslampe@lano-crew.org'),
('unitname', 'DasLampe'),
('version', '2.0.1')
('scoutNetId', '7');