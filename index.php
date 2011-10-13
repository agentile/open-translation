<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Open Translation Example</title>
<link rel="stylesheet" href="../css/ot.css" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script src="../js/open-translation.js"></script>
</head>
<body>
<p>Some example of text, select me.</p><br/>
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
