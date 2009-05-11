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

class Tx_SfChecklist_Library_TcaFields {
	public function getCheckLabel(&$params, &$pObj) {
		$feuser_row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$select = 'fe_users.username, tx_sfchecklist_domain_model_check.record_id, tx_sfchecklist_domain_model_check.record_table',
			$from = 'tx_sfchecklist_domain_model_check LEFT JOIN fe_users ON tx_sfchecklist_domain_model_check.fe_user = fe_users.uid',
			$where = 'tx_sfchecklist_domain_model_check.uid = ' . $params['row']['uid'],
			$groupBy = '',
			$orderBy = '',
			$limit = 1
		);

		$content_row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$select = $GLOBALS['TCA'][$feuser_row[0]['record_table']]['ctrl']['label'] . ' AS title',
			$from = $feuser_row[0]['record_table'],
			$where = 'uid = ' . $feuser_row[0]['record_id'],
			$groupBy = '',
			$orderBy = '',
			$limit = 1
		);

		$params['title'] = $feuser_row[0]['username'] . ' - ' . $content_row[0]['title'];
	}
}

class user_SfChecklist_Library_TcaFields extends Tx_SfChecklist_Library_TcaFields {
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Library/TcaFields.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Library/TcaFields.php']);
}

?>