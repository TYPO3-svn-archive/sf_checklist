<?php

class tx_SfChecklist_Library_TcaFields {
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Library/TcaFields.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Library/TcaFields.php']);
}

?>