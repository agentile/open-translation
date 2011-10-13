<?php
require 'ot/lib/locale.php';

$accepted_locales = array(
    'en' => 'en_US', 
    'es' => 'es_MX'
);

if (isset($_GET['lang']) && in_array($_GET['lang'], array_keys($accepted_languages))) {
    $locale_code = $accepted_languages[$_GET['lang']];
} else {
    $config = include 'ot/lib/config.php';
    $locale_code = $config['native_locale']['code']; 
}

$locale = new OT_Locale(array('code' => $locale_code, 'path' => dirname(__FILE__) . '/locales'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Open Translation Documentation</title>
<link rel="stylesheet" href="/ot/css/ot.css" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="/ot/js/open-translation.js"></script>
</head>
<body>
<header>
<h1 data-translatable-id="header"><?php echo $locale->fetch('WELCOME');?></h1>
<p data-translatable-id="project_description">This is the description of the open-translation project</p>
<p class="ot_translatable">Example using 'ot_translatable' class.</p>

<script type="text/javascript">
ot.init({
    native_locale: 'en_US',
    csrf_token: '',
    translate_type: 'all' // class, selected, all
});
</script>
</body>
</html>
