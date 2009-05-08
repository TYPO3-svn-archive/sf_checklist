<?php

class Tx_SfChecklist_Domain_Model_Listitem extends Tx_Extbase_DomainObject_AbstractEntity {
	public $table;

	/**
	 * @var string The pid
	 */
	protected $pid;

	protected $uid;

	/**
	 * @var string defines what field should be renderd as lable
	 */
	protected $label;

	protected $username;

	protected $checkRepository;

	protected function initializeObject() {
		$this->checkRepository = t3lib_div::makeInstance('Tx_SfChecklist_Domain_Model_CheckRepository');
	}

	public function getLabel() {
		return $this->label;
	}

	public function getCheckbox() {
		return $this->checkRepository->findByListitem($this);
	}

	public function getLoggedinUser() {
		if (!empty($GLOBALS['TSFE']->fe_user->user)) {
			return 'false';
		}

		return 'true';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/Listitem.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/Listitem.php']);
}

?>