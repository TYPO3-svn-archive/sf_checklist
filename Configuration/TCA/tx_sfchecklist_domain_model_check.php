<?php

if (!defined ('TYPO3_MODE')) die ('Access denied.');

$TCA['tx_sfchecklist_domain_model_check'] = array (
	'ctrl' => $TCA['tx_sfchecklist_domain_model_check']['ctrl'],
	'feInterface' => $TCA['tx_sfchecklist_domain_model_check']['feInterface'],
	'columns' => array (
		'fe_user' => Array (
			'label' => 'LLL:EXT:sf_checklist/Resources/Private/Language/locallang_db.xml:tx_sfchecklist_check.fe_user',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'fe_users',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
				'readOnly' => 1,
			)
		),

		'record_table' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_checklist/Resources/Private/Language/locallang_db.xml:tx_sfchecklist_check.record_table',
			'config' => Array (
				'type' => 'select',
				'allownonidvalues' => 1,
				'itemsProcFunc' => 'EXT:sf_checklist/Classes/Library/ItemProcFunc.php:user_Sfchecklist_Library_itemProcFunc->addTables',
				'readOnly' => 1,
			)
		),

		'record_id' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_checklist/Resources/Private/Language/locallang_db.xml:tx_sfchecklist_check.record_id',
			'config' => Array (
				'type' => 'select',
				'itemsProcFunc' => 'EXT:sf_checklist/Classes/Library/ItemProcFunc.php:user_Sfchecklist_Library_itemProcFunc->recordLabel',
				'readOnly' => 1,
			)
		),

		'plugin_id' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:sf_checklist/Resources/Private/Language/locallang_db.xml:tx_sfchecklist_check.plugin_id',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tt_content',
				'size' => 1,
				'readOnly' => 1,
			)
		),
	),

	'types' => array (
		'0' => array('showitem' => 'fe_user, record_table, record_id, plugin_id')
	),
);

?>