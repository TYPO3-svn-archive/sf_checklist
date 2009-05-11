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

class Tx_SfChecklist_Domain_Model_Check extends Tx_Extbase_DomainObject_AbstractEntity {
	protected $uid = 0;

	protected $feUser = 0;

	protected $recordId = 0;

	protected $recordTable = '';

	protected $pluginId = 0;

	public function getFeUser() {
		return $this->feUser;
	}

	public function getRecordId() {
		return $this->recordId;
	}

	public function getRecordTable() {
		return $this->recordTable;
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