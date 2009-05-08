<?php

class Tx_SfChecklist_Controller_ListController extends Tx_Extbase_MVC_Controller_ActionController {
	public $saved = false;

	public function initializeAction() {
		$this->listitemRepository = t3lib_div::makeInstance('Tx_SfChecklist_Domain_Model_ListitemRepository');
	}

	public function indexAction() {
		$records = $this->listitemRepository->findBySettings($this->settings);
		$this->view->assign('listitems', $records);

		$hiddenValues = array(array('name' => 'saved', 'value' => 1));
		if (isset($this->settings['considerPluginId'])) {
			$hiddenValues[] = array('name' => 'pluginid', 'value' => 1);
		}

		$this->view->assign('hiddenValues', $hiddenValues);
	}

	public function saveAction() {
		$this->saved = true;

print_r('das speichern muss noch implementiert ');
		//$this->redirect('index');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/ListController.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/ListController.php']);
}

?>