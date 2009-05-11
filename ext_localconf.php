<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TYPO3_CONF_VARS['FE']['eID_include']['tx_sfchecklist'] = 'EXT:sf_checklist/Classes/Controller/EidController.php';

?>