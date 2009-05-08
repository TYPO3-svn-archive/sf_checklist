<?php

require_once(t3lib_extMgm::extPath('sf_checklist') . 'Classes/Domain/TX_Sfchecklist_Domain_Request.php');
require_once(t3lib_extMgm::extPath('sf_checklist') . 'Classes/Domain/TX_Sfchecklist_Domain_Configuration.php');
require_once(t3lib_extMgm::extPath('sf_checklist') . 'Classes/Domain/TX_Sfchecklist_Domain_Feuser.php');
require_once(t3lib_extMgm::extPath('sf_checklist') . 'Classes/Domain/TX_Sfchecklist_Domain_Relation.php');
require_once(PATH_tslib . 'class.tslib_content.php');

/**
 * This is the eID handler to record changed checks on the list.
 *
 * @author	Sebastian Fischer <typo3@fischer.im>
 */
class TX_Sfchecklist_Controller_EidController {
	public $configurations = array();
	public $designator = 'sf_checklist';
	public $cObj;

	private function init() {
		tslib_eidtools::connectDB();

		$GLOBALS['TSFE']->fe_user = tslib_eidtools::initFeUser();
		$feuserClassName = t3lib_div::makeInstanceClassName('TX_Sfchecklist_Domain_Feuser');
		$this->feuser = new $feuserClassName();

		$configurationsClassName = t3lib_div::makeInstanceClassName('TX_Sfchecklist_Domain_Configuration');
		$this->configurations = new $configurationsClassName($conf);

		$requestClassName = t3lib_div::makeInstanceClassName('TX_Sfchecklist_Domain_Request');
		$this->request = new $requestClassName($this);

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->data['uid'] = $this->request->get('plugin');

		if ($this->request->get('plugin') > 0) {
			$this->configurations->set('considerPluginUid', $this->request->get('plugin'));
		}
	}

	/**
	 * Main function for the checklist AJAX call
	 *
	 * @return	void
	 */
	public function main() {
		$this->init();

		$this->saveAjax();
	}

	private function saveAjax() {
		$relationClassName = t3lib_div::makeInstanceClassName('TX_Sfchecklist_Domain_Relation');
		$relations = new $relationClassName($this, array());

		$fieldInfos = str_replace(array($this->designator . '[', ']'), '', htmlspecialchars($this->request->get('name')));
		list($table, $uid) = explode('[', $fieldInfos);

		$data = array(
			'fe_user' => (int) $this->feuser->get('uid'),
			'record_id' => (int) $uid,
			'record_table' => $this->secureString($table),
			'plugin_id' => (int) $this->request->get('plugin'),
		);
		$relation = new $relationClassName($this, $data);

		$out = array();
		if ($this->request->get('status') == 'true') {
			$relation->setForAdding();
			$out['msg'] = 'Reference added';
		} else {
			$relation->setForDeleting();
			$out['msg'] = 'Reference deleted';
		}

		$relations->append($relation);
		$relations->workRelations();

		header('Content-type: application/x-json');
		echo json_encode($out);
		die();
	}

	private function secureString($string) {
		$out = preg_replace("/[^a-zA-Z0-9_]/", '', $string);
		return $out;
	}
}

// Make instance:
$SOBE = t3lib_div::makeInstance('TX_Sfchecklist_Controller_EidController');
$SOBE->main();

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/TX_Sfchecklist_Controller_EidController.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/TX_Sfchecklist_Controller_EidController.php']);
}

?>