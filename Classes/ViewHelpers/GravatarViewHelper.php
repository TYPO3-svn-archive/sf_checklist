<?php

/**
 *
 * @package Fluid
 * @subpackage ViewHelpers
 * @scope prototype
 */
class Tx_SfChecklist_Viewhelpers_GravatarViewHelper extends Tx_Fluid_Core_AbstractViewHelper {

	/**
	 * @param string $email The email address of the gravatar
	 * @validate $email EmailAdress
	 * @return string the rendered string
	 * @author Sebastian Fischer <typo3@fischer.im>
	 */
	public function render($email) {
		return '<img src="http://www.gravatar.com/avatar/' . md5($email) . '"/>';
	}
}

?>