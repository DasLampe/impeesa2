UPDATE  `impeesa2_config` SET  `config_value` =  '2.0.3' WHERE  `config_key` =  'version';
ALTER TABLE  `impeesa2_users` ADD  `can_contact` TEXT NOT NULL AFTER  `email`;