<?php
// load some things we want
require dirname(dirname(__FILE__)) . '/lib/ot.php';
$db_config = OT::getConfigKey('database');
$db = OT::getObject('OT_DB', $db_config); 
$view = OT::getObject('OT_View');

$pages = $db->fetchPages();

if (isset($_GET['native_code'])) { 
    $from = $_GET['native_code'];
} else {
    $nl_config = OT::getConfigKey('native_locale');
    $from = $nl_config['code'];
}

$url = (isset($_GET['url'])) ? $_GET['url'] : null;
$to = (isset($_GET['translated_code'])) ? $_GET['translated_code'] : null;

$results = $db->fetchAllTranslations($url, $from, $to);

$locales = OT::getConfigKey('available_locales', array());
$view->setLocale(array('code' => 'en_US', 'path' => OT::$system . '/admin/locales'));
$view->render(
    array(
        'selected_translated_code' => $to,
        'selected_native_code' => $from,
        'selected_page' => $url,
        'translations' => $results, 
        'locales' => $locales,
        'host' => $_SERVER['HTTP_HOST'],
        'pages' => $pages,
    ), 'index/main');
?>
