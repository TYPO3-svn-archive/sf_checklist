<?php

class Tx_SfChecklist_Domain_Model_Listitem extends Tx_Extbase_DomainObject_AbstractEntity {
	protected $settings = array();

	/**
	 * @var string the tablename
	 */
	protected $table;

	/**
	 * @var integer the pid
	 */
	protected $pid;

	/**
	 * @var integer the uid
	 */
	protected $uid;

	/**
	 * @var string defines what field should be renderd as label
	 */
	protected $label;

	#protected $username;

	/**
	 * @var Tx_SfChecklist_Domain_Model_CheckRepository repository to get checkbox from
	 */
	protected $checkRepository;

	protected function initializeObject() {
		$this->checkRepository = t3lib_div::makeInstance('Tx_SfChecklist_Domain_Model_CheckRepository');
	}

	public function setSettings($settings) {
		$this->settings = $settings;
	}

	public function getCheckbox() {
		$this->checkRepository->setSettings($this->settings);
		return $this->checkRepository->findByListitem($this);
	}

	/**
	 * Getter for label
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Getter for tablename
	 *
	 * @return string
	 */
	public function setTable($value) {
		return $this->table = $value;
	}

	/**
	 * Getter for tablename
	 *
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	public function addCheck() {
		$this->checkRepository->setSettings($this->settings);
		$this->checkRepository->addCheck($this);
	}

	public function removeCheck() {
		$this->checkRepository->setSettings($this->settings);
		$this->checkRepository->removeCheck($this);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/Listitem.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/Listitem.php']);
}

?>