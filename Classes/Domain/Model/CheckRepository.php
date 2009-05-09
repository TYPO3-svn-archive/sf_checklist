<?php

class Tx_SfChecklist_Domain_Model_CheckRepository extends Tx_Extbase_Persistence_Repository {
	public function findByListitem(Tx_Extbase_DomainObject_AbstractEntity $listitem) {
		$conditions = array();
		$conditions['feUser'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		$conditions['recordId'] = $listitem->getUid();
		$conditions['recordTable'] = $listitem->getTable();

		// TODO das Auslesen ob PluginID berücksichtigt werden soll muss noch umgesetzt werden
		if ($listitem->controller) {
			$conditions['pluginId'] = '';
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
				'plugin_id' => '',
				'record_id' => $listitem->getUid(),
				'record_table' => $listitem->getTable(),
			);

			// TODO das Auslesen ob PluginID berücksichtigt werden soll muss noch umgesetzt werden
			if ($listitem->controller) {
				$insertData['plugin_id'] = '';
			}

			$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_sfchecklist_domain_model_check',
				$insertData
			);
		}
	}

	public function removeCheck($listitem) {
		$checkbox = $this->findByListitem($listitem);

		$where = 'uid = ' . $checkbox[0]->getUid();
		// TODO das Auslesen ob PluginID berücksichtigt werden soll muss noch umgesetzt werden
		if ($listitem->controller) {
			$insertData['plugin_id'] = '';
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