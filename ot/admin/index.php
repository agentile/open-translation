<?php
// we will load some lib files here
// and then display out view.
require dirname(dirname(__FILE__)) . '/lib/ot.php';
$ot = new OT();
$ot->start();
$ot->view->render(array(), 'index/main');
?>
