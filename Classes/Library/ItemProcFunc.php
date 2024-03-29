<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Sebastian Fischer <typo3@fischer.im>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class Tx_SfChecklist_Library_itemProcFunc {
	private $disallowedTables = array(
		'be_users',
		'be_groups',
		'pages',
		'pages_language_overlay',
		'static_template',
		'sys_domain',
		'sys_filemounts',
		'sys_language',
		'sys_note',
		'sys_template',
		'sys_workspace',
		'tx_rtehtmlarea_acronym',
		'tx_sfchecklist_domain_model_checks',
	);

	public function addTables($config) {
		$TCA = $GLOBALS['TCA'];
		foreach($this->disallowedTables as $table) {
			if(array_key_exists($table, $TCA)) {
				unset($TCA[$table]);
			}
		}

		$additionalItems = array();
		foreach($TCA as $tableName => $tableConf) {
			if ($GLOBALS['LANG']->sL($tableConf['ctrl']['title']) != '') {
				$additionalItems[] = array($GLOBALS['LANG']->sL($tableConf['ctrl']['title']), $tableName);
			}
		}

		sort($additionalItems);

		$config['items'] = array_merge($config['items'], $additionalItems);
		return $config;
	}

	public function recordLabel($config) {
		$config['foreign_table'] = $config['row']['record_table'];

		$content_row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$select = $GLOBALS['TCA'][$config['foreign_table']]['ctrl']['label'] . ' AS title, uid',
			$from = $config['foreign_table'],
			$where = 'uid = ' . $config['row']['record_id'],
			$groupBy = '',
			$orderBy = '',
			$limit = 1
		);

		$config['items'] = array(array($content_row[0]['title'], $config['row']['record_id']));

		return $config;
	}
}

class user_Sfchecklist_Library_itemProcFunc extends Tx_SfChecklist_Library_itemProcFunc {
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Library/ItemProcFunc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Library/ItemProcFunc.php']);
}

?>