<?php

class Tx_SfChecklist_Domain_Model_Check extends Tx_Extbase_DomainObject_AbstractEntity {
	protected $uid = 0;

	protected $feUser = 0;

	protected $recordId = 0;

	protected $recordTable = '';

	protected $pluginId = 0;

	public function getFeUser() {
		return $this->feUser;
	}

	public function getName() {
		$name = array(
			$this->recordTable,
			$this->recordId,
		);

		return implode('_', $name);
	}

	public function getChecked() {
		if ($this->uid > 0) {
			return 'checked="checked"';
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/Check.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/Check.php']);
}

?>