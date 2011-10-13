<?php
// load some things we want
require dirname(dirname(__FILE__)) . '/lib/ot.php';
$db_config = OT::getConfigKey('database');
$db = OT::getObject('OT_DB', $db_config); 
$view = OT::getObject('OT_View');


$results = $db->fetchAllTranslations();
$view->setLocale(array('code' => 'en_US', 'path' => OT::$system . '/admin/locales'));
$view->render(array('translations' => $results), 'index/main');
?>
