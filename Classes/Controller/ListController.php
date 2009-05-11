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

class Tx_SfChecklist_Controller_ListController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_SfChecklist_Domain_Model_ListitemRepository
	 */
	protected $listitemRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->listitemRepository = t3lib_div::makeInstance('Tx_SfChecklist_Domain_Model_ListitemRepository');
		// TODO pluginId aus pObj->data['uid'] nehmen
		$this->settings['pluginId'] = '8';
		// TODO setSettings in CheckRepository, ListitemRepository und ListItem checken
		$this->listitemRepository->setSettings($this->settings);
	}

	/**
	 * Index action for this controller. Displays a list of checklistitems.
	 *
	 * @return string The rendered view
	 */
	public function indexAction() {
		$this->view->assign('listitems', $this->listitemRepository->findBySettings($this->settings));

		$hiddenValues = array(array('name' => 'saved', 'value' => 1));
		if ($this->settings['considerPluginUid'] = 1) {
			$hiddenValues[] = array('name' => 'pluginid', 'value' => $this->settings['pluginId']);
		}

		$this->view
			->assign('hiddenValues', $hiddenValues)
			->assign('loggedinUser', $this->getLoggedinUser());
	}

	/**
	 * Save checks
	 *
	 * @return void
	 */
	public function saveAction() {
		$records = $this->listitemRepository->findBySettings($this->settings);
		$checks = $this->request->getArgument('check');

		foreach ($records as $listItem) {
			if (isset($checks[$listItem->getTable()][$listItem->getUid()])) {
				$listItem->addCheck();
			} else {
				$listItem->removeCheck();
			}
		}

		$this->redirect('index');
	}

	/**
	 * Edits an existing post
	 *
	 * @return boolean Form for editing the existing post
	 */
	public function getLoggedinUser() {
		if (!empty($GLOBALS['TSFE']->fe_user->user)) {
			return 'true';
		}

		return 'false';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/ListController.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/Controller/ListController.php']);
}

?>