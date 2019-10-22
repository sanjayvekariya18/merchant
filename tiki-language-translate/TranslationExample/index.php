<?php
/**
 * This file is for add/update language translation to user with manually/bing api.
 * @return null
 * */
define('TRANSLATION_CONFIGURATION_FILE_PATH','../lib/TranslationConfiguration.php');
define('SET_UNIVERSAL_TIMEZONE','UTC');
define('ASSIGN_KENDO_ACCESS_FILE_PATH','kendoAccessFilepath');
define('DISPLAY_SMARTY_LANGUAGE_TRANSLATOR_FILENAME','LanguageTranslator.tpl');

include TRANSLATION_CONFIGURATION_FILE_PATH;
require_once(SMARTY_ACCESS_FILEPATH);
date_default_timezone_set(SET_UNIVERSAL_TIMEZONE);
$smartyObject=new Smarty();
$smartyObject->assign(ASSIGN_KENDO_ACCESS_FILE_PATH, KENDO_ACCESS_FILEPATH);
$smartyObject->display(DISPLAY_SMARTY_LANGUAGE_TRANSLATOR_FILENAME);
?>
