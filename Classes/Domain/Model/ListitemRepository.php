<?php

require_once(PATH_tslib . 'class.tslib_content.php');

class Tx_SfChecklist_Domain_Model_ListitemRepository extends Tx_Extbase_Persistence_Repository {
	public function findBySettings(array $settings) {
		if (!is_object($this->cObj)) {
			$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		}

		switch ($settings['source']) {
			case 'pages':
				return $this->findByPages($settings);
				break;
			case 'records':
				return $this->findByRecords($settings['records']);
				break;
		}
	}

	public function findByPages(array $settings) {
		$objects = array();
		$dataMapper = t3lib_div::makeInstance('Tx_Extbase_Persistence_Mapper_ObjectRelationalMapper');
		$pidList = $this->getPidlistRecurcive($settings['pages'], $settings['recursive']);

		$tables = explode(',', $settings['tables']);
		if (count($tables) > 0) {
			foreach($tables as $table) {
				$where = 'pid in (' . $pidList . ')';
				$enableFields = $GLOBALS['TSFE']->sys_page->enableFields($table);

				$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'*',
					$from = $table,
					$where . $enableFields,
					$groupBy,
					$orderBy,
					$limit,
					$uidIndexField
				);

				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$properties = $row;
						$properties['label'] = $row[$GLOBALS['TCA'][$table]['ctrl']['label']];

						$listItem = $dataMapper->reconstituteObject('Tx_SfChecklist_Domain_Model_Listitem', $properties);
						$listItem->table = $table;
						$objects[] = $listItem;
					}
				}
			}
		}
		return $objects;
	}

	public function findByRecords($recordList) {
		$objects = array();
		$dataMapper = t3lib_div::makeInstance('Tx_Extbase_Persistence_Mapper_ObjectRelationalMapper');

		$records = explode(',', $recordList);
		if (count($records) > 0) {
			foreach($records as $record) {
				$recordParts = explode('_', $record);
				array_pop($recordParts);
				$table = implode('_', $recordParts);
				$uid = str_replace($table . '_', '', $record);

				$where = 'uid = ' . $uid;
				$enableFields = $GLOBALS['TSFE']->sys_page->enableFields($table);

				$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'*',
					$from = $table,
					$where . $enableFields,
					$groupBy,
					$orderBy,
					$limit,
					$uidIndexField
				);

				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$properties = $row;
						$properties['label'] = $row[$GLOBALS['TCA'][$table]['ctrl']['label']];

						$listItem = $dataMapper->reconstituteObject('Tx_SfChecklist_Domain_Model_Listitem', $properties);
						$listItem->table = $table;
						$objects[] = $listItem;
					}
				}
			}
		}

		return $objects;
	}

	public function getPidlistRecurcive($pid_list, $recursive = 0) {
		if (!strcmp($pid_list, '')) {
			$pid_list = $GLOBALS['TSFE']->id;
		}

		$recursive = t3lib_div::intInRange($recursive, 0);

		$pid_list_arr = array_unique(t3lib_div::trimExplode(',', $pid_list, 1));
		$pid_list     = array();

		foreach($pid_list_arr as $val) {
			$val = t3lib_div::intInRange($val, 0);
			if ($val) {
				$_list = $this->cObj->getTreeList(-1 * $val, $recursive);
				if ($_list) {
					$pid_list[] = $_list;
				}
			}
		}

		return implode(',', $pid_list);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/ListitemRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Domain/Model/ListitemRepository.php']);
}

?>