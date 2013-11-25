
CREATE TABLE `tl_iso_payment_modules` (
    `payunity_sandbox` char(1) NOT NULL default '',
    `payunity_live_sender_id` varchar(64) NOT NULL default '',
    `payunity_live_channel_id` varchar(64) NOT NULL default '',
    `payunity_live_user_id` varchar(64) NOT NULL default '',
    `payunity_live_user_pwd` varchar(64) NOT NULL default '',
    `payunity_test_sender_id` varchar(64) NOT NULL default '',
    `payunity_test_channel_id` varchar(64) NOT NULL default '',
    `payunity_test_user_id` varchar(64) NOT NULL default '',
    `payunity_test_user_pwd` varchar(64) NOT NULL default '',
    `payunity_redirect_success` int(10) unsigned NOT NULL default '0',
    `payunity_redirect_checkout` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;