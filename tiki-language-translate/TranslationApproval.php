<?php
/**
 * This file is for admin side add/update and approval translation after correction.
 * @return null
 * */
define('TRANSLATION_CONFIGURATION_FILE_PATH','lib/TranslationConfiguration.php');
define('SET_UNIVERSAL_TIMEZONE','UTC');
define('ASSIGN_KENDO_ACCESS_FILE_PATH','kendoAccessFilepath');
define('DISPLAY_SMARTY_TRANSLATION_APPROVAL_FILENAME','TranslationApproval.tpl');

include TRANSLATION_CONFIGURATION_FILE_PATH;
require_once(SMARTY_ACCESS_FILEPATH);
date_default_timezone_set(SET_UNIVERSAL_TIMEZONE);
$smartyObject=new Smarty();
$smartyObject->assign(ASSIGN_KENDO_ACCESS_FILE_PATH, KENDO_ACCESS_FILEPATH);
$smartyObject->display(DISPLAY_SMARTY_TRANSLATION_APPROVAL_FILENAME);
?>

