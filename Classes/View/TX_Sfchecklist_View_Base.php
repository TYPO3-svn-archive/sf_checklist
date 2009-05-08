<?php

class TX_Sfchecklist_View_Base {
	protected $pObj;
	protected $renderContent;
	protected $hiddenValues = array();

	/**
	 * constructor expects a tx_sfchecklist_DefaultController object
	 */
	public function __construct(tx_Sfchecklist_Controller_ListController &$pObj) {
		$this->pObj =& $pObj;
		$this->cObj =& $pObj->cObj;
		$this->renderContent = $this->pObj->configurations->get('renderContent');

		if ($this->pObj->configurations->get('considerPluginUid')) {
			$this->setHiddenValue('plugin', $this->pObj->cObj->data['uid']);
		}

		if (!$this->pObj->configurations->get('disableJSinclude')) {
			$GLOBALS['TSFE']->additionalHeaderData['sf_checklist_jquery'] = '<script src="typo3conf/ext/sf_checklist/Resources/Media/Scripts/jquery-1.3.1.min.js" type="text/javascript"></script>';
			$GLOBALS['TSFE']->additionalHeaderData['sf_checklist_functions'] = '<script src="typo3conf/ext/sf_checklist/Resources/Media/Scripts/functions.js" type="text/javascript"></script>';
		}
		if (!$this->pObj->configurations->get('disableCSSinclude')) {
			$GLOBALS['TSFE']->additionalHeaderData['sf_checklist_styles'] = '<link rel="stylesheet" type="text/css" href="typo3conf/ext/sf_checklist/Resources/Media/CSS/styles.css" />';
		}
	}

	/**
	 * sets a hidden Value. As there could be only one value with a given name
	 * its not added but set to overwrite existing values with same index.
	 */
	protected function setHiddenValue($index, $value) {
		$this->hiddenValues[$index] = $value;
	}

	/**
	 * removes a value with the given index from the hiddenValue array.
	 */
	protected function removeHiddenValue($index) {
		if (array_key_exists($index, $this->hiddenValues)) {
			unset($this->hiddenValues[$index]);
		}
	}

	/**
	 * renders input tags type hidden for every value in the hiddenValue array
	 */
	protected function renderHiddenValues() {
		reset($this->hiddenValues);
		$content = array();
		foreach($this->hiddenValues as $hiddenName => $hiddenValue) {
			$content[] = '<input type="hidden" name="' . $hiddenName . '" id="' . $hiddenName . '" value="' . $hiddenValue . '" />';
		}

		return implode("\n", $content);
	}

	/**
	 * returns the url for the current page
	 */
	protected function getActionUrl() {
		$conf['typolink.'] = array(
			'parameter' => $GLOBALS['TSFE']->id,
			'returnLast' => 'url',
		);
		return $this->cObj->stdWrap('', $conf);
	}

	/**
	 * renders the content that is displayed next to the inputfields
	 */
	protected function getContent($entry) {
		if ($this->renderAsTitle()) {
			return $entry->get('title');
		}
		if ($this->renderAsContent()) {
			$content = $this->cObj->RECORDS(array(
				'tables' => $entry->get('table'),
				'source' => $entry->get('uid'),
				'conf.' => array(
					$entry->get('table') => $GLOBALS['TSFE']->tmpl->setup[$entry->get('table')],
					$entry->get('table') . '.' => $GLOBALS['TSFE']->tmpl->setup[$entry->get('table') . '.'],
				),
				'dontCheckPid' => 1,
			));
			return $content;
		}
	}

	/**
	 * renders a checkbox for the entry
	 */
	protected function renderCheckbox($entry) {
		if ($this->isAuthenticated()) {
			$checked = $entry->get('checked') ? ' checked="checked"' : '';
			return '<input type="checkbox" name="' . $this->pObj->designator .
				'[' . $entry->get('table') . '][' . $entry->get('uid') .
				']" value="checked"' . $checked . ' />';
		}
		return '%%%LANG_NOTLOGGEDIN%%%';
	}

	/**
	 * renders a radiobutton for the entry
	 */
	protected function renderRadiobutton($entry) {
		if ($this->isAuthenticated()) {
			$checked = $entry->get('checked') ? ' checked="checked"' : '';
			return '<input type="radio" name="' . $this->pObj->designator .
				'[radiogroup]" value="' . $entry->get('table') . '_' .
				$entry->get('uid') .'"' . $checked . ' />';
		}
		return '%%%LANG_NOTLOGGEDIN%%%';
	}

	/**
	 * checkes if renderContent is set to Title and returns true/false
	 */
	protected function renderAsTitle() {
		if ($this->renderContent == 'Title') {
			return true;
		}

		return false;
	}

	/**
	 * checkes if renderContent is set to Content and returns true/false
	 */
	protected function renderAsContent() {
		if ($this->renderContent == 'Content') {
			return true;
		}

		return false;
	}

	/**
	 * checkes if an userdata are set and returns true/false
	 * these are only set if the users is logged in
	 */
	protected function isAuthenticated() {
		if (is_array($GLOBALS['TSFE']->fe_user->user)) {
			return true;
		}

		return false;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/View/TX_Sfchecklist_View_Base.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_checklist/Classes/View/TX_Sfchecklist_View_Base.php']);
}

?>