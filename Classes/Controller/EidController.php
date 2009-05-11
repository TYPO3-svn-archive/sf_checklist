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

require_once(PATH_tslib . 'class.tslib_content.php');

/**
 * This is the eID handler to record changed checks on the list.
 *
 * @author	Sebastian Fischer <typo3@fischer.im>
 */
class TX_SfChecklist_Controller_EidController {

	/**
	 * @var string
	 */
	protected $designator = 'tx_sfchecklist_list';

	/**
	 * @var t3lib_cObj
	 */
	protected $cObj;

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * @var boolean
	 */
	protected $debug = false;

	/**
	 * initialize needed variables and objects
	 *
	 * @return	void
	 */
	private function init() {
		$this->request = $_REQUEST[$this->designator];

		tslib_eidtools::connectDB();
		$GLOBALS['TSFE']->fe_user = tslib_eidtools::initFeUser();

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->data['uid'] = (int) $this->request['pluginid'];

		$fieldInfos = str_replace(array($this->designator . '[check][', ']'), '', htmlspecialchars($this->request['name']));
		list($table, $uid) = explode('[', $fieldInfos);

		$this->settings = array(
			'fe_user' => (int) $GLOBALS['TSFE']->fe_user->user['uid'],
			'record_id' => (int) $uid,
			'record_table' => $this->secureString($table),
			'plugin_id' => (int) $this->request['pluginid'],
		);

		if ($this->debug) {
			debug($this->settings);
			$GLOBALS['TYPO3_DB']->debugOutput = 1;
		} else {
			$this->out['debug'] =
				$this->settings['fe_user'] . "\n" .
				$this->settings['record_id'] . "\n" .
				$this->settings['record_table'] . "\n" .
				$this->settings['plugin_id'] . "\n";
		}
	}

	/**
	 * Main function for the checklist AJAX call
	 *
	 * @return	void
	 */
	public function main() {
		$this->init();

		switch ($this->request['status']) {
			case 'true':
				$this->out['msg'] .= $this->addCheck();
				break;
			case 'false':
				$this->out['msg'] .= $this->removeCheck();
				break;
		}

		if (empty($this->out['msg'])) {
			$this->out['err'] = 'ups nothing done';
		}

		if (!$this->debug) {
			header('Content-type: application/x-json');
		}
		echo json_encode($this->out);
		die();
	}

	/**
	 * checks wethere a check is saved or not and if not saves it to db
	 *
	 * @return string message with taken action
	 */
	protected function addCheck() {
		$additionalWhere = 'plugin_id = ' . $this->settings['plugin_id'];
		$existingChecks = $this->getChecks($additionalWhere);

		if (empty($existingChecks[0])) {
			$insertData = $this->settings;

			$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_sfchecklist_domain_model_check',
				$insertData
			);

			$out = 'Reference added';
		} else {
			$out = 'Reference already exists';
		}
		return $out;
	}

	/**
	 * checks wether a check is saved or not and if it is deleted the check from db
	 *
	 * @return string message with taken action
	 */
	protected function removeCheck() {
		$additionalWhere = '(plugin_id = ' . $this->settings['plugin_id'] . ' OR plugin_id = 0)';
		$existingChecks = $this->getChecks($additionalWhere);

		if (!empty($existingChecks[0])) {
			$where = 'uid = ' . $existingChecks[0]['uid'];
			$GLOBALS['TYPO3_DB']->exec_DELETEquery(
				'tx_sfchecklist_domain_model_check',
				$where
			);
			$out = 'Reference deleted';
		} else {
			$out = 'No reference to delete found';
		}

		return $out;
	}

	/**
	 * fetches checks for given request
	 *
	 * @return array
	 */
	protected function getChecks($additionalWhere = '') {
		$where = array(
			'fe_user = ' . $this->settings['fe_user'],
			'record_id = ' . $this->settings['record_id'],
			'record_table = \'' . $this->settings['record_table'] .'\'',
		);
		/*
		 *  TODO macht das Sinn auf plugin_id nur dann zu prüfen wenn eine gesetzt ist?
		 *  was ist mit Checks die aus Listen stammen die die ID schreiben und dann
		 *  in einer Liste gelöscht werden könnten die die PluginID nicht berücksichtigt
		 */
		if ($this->settings['plugin_id'] > 0) {
			//$where[] = 'plugin_id = ' . $this->settings['plugin_id'];
		}
		$where[] = $additionalWhere;
		$where = implode(' AND ', $where);

		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			$from = 'tx_sfchecklist_domain_model_check',
			$where
		);

		return $rows;
	}

	/**
	 * filters every character beside a-Z, 0-9 and underscore
	 *
	 * @return string
	 */
	protected function secureString($string) {
		return preg_replace("/[^a-zA-Z0-9_]/", '', $string);
	}
}

// Make instance:
$SOBE = t3lib_div::makeInstance('TX_SfChecklist_Controller_EidController');
$SOBE->main();

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/EidController.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/EidController.php']);
}

?>