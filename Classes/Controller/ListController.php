<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2009 Sebastian Fischer <sebastian@fischer.im>
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
	}

	/**
	 * Index action for this controller. Displays a list of checklistitems.
	 *
	 * @return string The rendered view
	 */
	public function indexAction() {
		$this->view->assign('listitems', $this->listitemRepository->findBySettings($this->settings));

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