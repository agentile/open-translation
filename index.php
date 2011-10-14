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
$locale = new OT_Locale(array('code' => $locale_code, 'path' => dirname(__FILE__) . '/locales', 'fallback_code' => 'en_US'));
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
  
  <a href="/#translate">Turn On Translation</a>
  
  <div id="demo-contain">
    <header>
      <h1><?php echo $locale->fetch('OPEN-TRANSLATION'); ?></h1>
      <h2><?php echo $locale->fetch('TAGLINE'); ?></h2>
    </header>

    <p><?php echo $locale->fetch('DEMO-PARA-1'); ?></p>
    <p><?php echo $locale->fetch('DEMO-PARA-2'); ?></p>
    <p><?php echo $locale->fetch('DEMO-PARA-3'); ?></p>

    <ul>
      <li data-translatable-id="click-here"><?php echo $locale->fetch('CLICK-HERE'); ?></li>
      <li data-translatable-id="welcome"><?php echo $locale->fetch('WELCOME'); ?></li>
      <li data-translatable-id="save-your-progress"><?php echo $locale->fetch('SAVE-YOUR-PROGRESS'); ?></li>
      <li data-translatable-id="make-new-entry"><?php echo $locale->fetch('MAKE-NEW-ENTRY'); ?></li>
    </ul>

    <p><?php echo $locale->fetch('DEMO-PARA-4'); ?></p>
    <p><?php echo $locale->fetch('DEMO-PARA-5'); ?></p>
    <p><?php echo $locale->fetch('DEMO-PARA-6'); ?></p>
    <p><?php echo $locale->fetch('DEMO-PARA-7'); ?></p>
  </div><!-- .demo-contain -->
  
  
  <!-- <div class="ot-mode-indicator">
    <h1>My language:</h1>
    <span class="ot-mode-language">French</span>
  </div> -->
  
  



  <!-- <input type="text" class="ot-add-translation" />
  <blockquote class="ot-translate-this"><?php echo $locale->fetch('TRANSLATE_THIS');?></blockquote>
  <a href="#" class="ot-submit ot-pill"><?php echo $locale->fetch('SUBMIT');?></a>
  
  <ul class="ot-submitted-translations-list">
    <li class="ot-list-green">
      Some text
      <span>+3</span>
    </li>
    
    <li class="ot-list-green">
      More text
      <span>+2</span>
    </li>
    
    <li class="ot-list-red">
      Extra stuff
      <span>-3</span>
    </li>
  </ul>

   <div class="ot-translateable ot-need-translation"></div>
   <div class="ot-translateable ot-has-translation"></div> -->



	<script type="text/javascript">
	ot.init({
	    native_locale: 'en_US',
	    csrf_token: '',
	    translate_type: 'all' // class, selected, all
	});
	</script>
</body>
</html>
