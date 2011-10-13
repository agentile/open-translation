<?php
// load some things we want
require dirname(dirname(__FILE__)) . '/lib/ot.php';
$db_config = OT::getConfigKey('database');
$db = OT::getObject('OT_DB', $db_config); 
$view = OT::getObject('OT_View');


// Handle Manuel Entry
if (isset($_POST['submit'])) {
    $db->insertEntry($_POST['url'], $_POST['native_code'], $_POST['native_text'], $_POST['translated_code'], $_POST['translated_text'], OT::getIP());
}

$results = $db->fetchAllTranslations();
$locales = OT::getConfigKey('available_locales', array());
$view->setLocale(array('code' => 'en_US', 'path' => OT::$system . '/admin/locales'));
$view->render(array('translations' => $results, 'locales' => $locales), 'index/main');
?>
