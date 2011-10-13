<?php
// we will load some lib files here
// and then display out view.
require dirname(dirname(__FILE__)) . '/lib/ot.php';
$ot = new OT();
$ot->start();
$results = $ot->database->fetchAllTranslations();
$ot->view->render(array('translations' => $results), 'index/main');
?>
