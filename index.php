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
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Open Translation Documentation</title>
	<link rel="stylesheet" href="/ot/styles/css/ot.css<?php echo '?' . filemtime(dirname(__FILE__) . '/ot/styles/css/ot.css');?>" type="text/css" />
	<script src="/ot/js/jq1.6.2.js<?php echo '?' . filemtime(dirname(__FILE__) . '/ot/js/jq1.6.2.js');?>"></script>
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="/ot/js/spin.js<?php echo '?' . filemtime(dirname(__FILE__) . '/ot/js/spin.js');?>" type="text/javascript" charset="utf-8"></script>
	<script src="/ot/js/open-translation.js<?php echo '?' . filemtime(dirname(__FILE__) . '/ot/js/open-translation.js');?>"></script>
</head>
<body>
	<header>
		<h1 data-translatable-id="header"><?php echo $locale->fetch('WELCOME');?></h1>
		<p data-translatable-id="project_description">This is the description of the open-translation project</p>
	</header>
	<hr />
	<h2 data-translatable-id="instructions_header">How to use Open-Translation</h2>
	<h3>
	<p data-translatable-id="example_text">Example using 'data-translatable-id' attribute class.</p>

	<script type="text/javascript">
	ot.init({
	    native_locale: 'en_US',
	    csrf_token: '',
	    translate_type: 'all' // class, selected, all
	});
	</script>
</body>
</html>
