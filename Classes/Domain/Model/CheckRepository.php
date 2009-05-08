<?php

class Tx_SfChecklist_Domain_Model_CheckRepository extends Tx_Extbase_Persistence_Repository {
	public function findByListitem(Tx_Extbase_DomainObject_AbstractEntity $listItem) {
		$conditions = array();
		$conditions['feUser'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		$conditions['recordId'] = $listItem->getUid();
		$conditions['recordTable'] = $listItem->table;
		if ($listItem->controller) {
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
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/CheckRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/CheckRepository.php']);
}

?>