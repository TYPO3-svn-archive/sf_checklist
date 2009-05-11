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

class Tx_SfChecklist_Domain_Model_CheckRepository extends Tx_Extbase_Persistence_Repository {
	protected $settings = array();

	public function setSettings($settings) {
		$this->settings = $settings;
	}

	public function findByListitem(Tx_Extbase_DomainObject_AbstractEntity $listitem) {
		$conditions = array();
		$conditions['feUser'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		$conditions['recordId'] = $listitem->getUid();
		$conditions['recordTable'] = $listitem->getTable();

		if ($this->settings['considerPluginUid']) {
			$conditions['pluginId'] = $this->settings['pluginId'];
		}

		$result = $this->findByConditions($conditions);

		if (!count($result)) {
			$row = array (
				'recordId' => $conditions['recordId'],
				'recordTable' => $conditions['recordTable'],
			);
			$dataMapper = t3lib_div::makeInstance('Tx_Extbase_Persistence_Mapper_ObjectRelationalMapper');
			$result[] = $dataMapper->reconstituteObject('Tx_SfChecklist_Domain_Model_Check', $row);
		}

		return $result;
	}

	public function addCheck($listitem) {
		$checkbox = $this->findByListitem($listitem);
		if ($checkbox[0]->getUid() == 0) {
			$insertData = array(
				'fe_user' => $GLOBALS['TSFE']->fe_user->user['uid'],
				'plugin_id' => 0,
				'record_id' => $listitem->getUid(),
				'record_table' => $listitem->getTable(),
			);

			if ($this->settings['considerPluginUid']) {
				$insertData['plugin_id'] = $this->settings['pluginId'];
			}

			$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_sfchecklist_domain_model_check',
				$insertData
			);
		}
	}

	public function removeCheck($listitem) {
		$checkbox = $this->findByListitem($listitem);

		$where = 'uid = ' . $checkbox[0]->getUid() . ' AND record_table = \'' . $checkbox[0]->getTable() . '\'';
		if ($this->settings['considerPluginUid'] && $this->settings['pluginId'] != '') {
			$where .= ' AND plugin_id = ' . $this->settings['pluginId'];
		}

		if ($checkbox[0]->getUid() > 0) {
			$GLOBALS['TYPO3_DB']->exec_DELETEquery(
				'tx_sfchecklist_domain_model_check',
				$where
			);
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/CheckRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/CheckRepository.php']);
}

?>