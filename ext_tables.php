<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sf_checklist']);

$TCA['tx_sfchecklist_domain_model_check'] = array (
	'ctrl' => array (
		'title'              => 'LLL:EXT:sf_checklist/Resources/Private/Language/locallang_db.xml:tx_sfchecklist_check',
		'label'              => 'fe_user',
		'label_userFunc'     => 'EXT:sf_checklist/Classes/Library/TcaFields.php:tx_Sfchecklist_Library_TcaFields->getCheckLabel',
		'label_alt_force'    => 1,
		'hideTable'          => $confArr['hideFromList'],
		'tstamp'             => 'tstamp',
		'crdate'             => 'crdate',
		'cruser_id'          => 'cruser_id',
		'default_sortby'     => 'ORDER BY fe_user, record_table, record_id',
		'dynamicConfigFile'  => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tx_sfchecklist_domain_model_check.php',
		'iconfile'           => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Private/Icons/tx_sfchecklist_domain_model_check.gif',
	),
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Feuser Checklist');

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_list'] = 'pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_list'] = 'layout,select_key,pages,recusive';

t3lib_extMgm::addPiFlexFormValue(
	$_EXTKEY . '_list',
	'FILE:EXT:sf_checklist/Configuration/Flexforms/flexform_checklist.xml'
);

t3lib_extMgm::addPlugin(
	array(
		'LLL:EXT:sf_checklist/Resources/Private/Language/locallang_db.xml:tt_content.checklist_list',
		$_EXTKEY . '_list',
	),
	'list_type'
);

?>