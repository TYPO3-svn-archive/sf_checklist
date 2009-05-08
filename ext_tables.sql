#
# Table structure for table 'tx_sfchecklist_check'
#

CREATE TABLE tx_sfchecklist_domain_model_check (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,

	fe_user int(11) DEFAULT '0' NOT NULL,
	plugin_id int(11) DEFAULT '0' NOT NULL,
	record_id int(11) DEFAULT '0' NOT NULL,
	record_table tinytext NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);