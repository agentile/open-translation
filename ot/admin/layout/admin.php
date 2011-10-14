<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OT Admin</title>
<link rel="stylesheet" href="/ot/admin/css/style.css<?php echo '?' . filemtime(OT::$system . '/admin/css/style.css');?>" type="text/css" />
<script src="/ot/js/jq1.6.2.js<?php echo '?' . filemtime(OT::$system . '/js/jq1.6.2.js');?>"></script>
<script src="/ot/admin/js/ot-admin.js<?php echo '?' . filemtime(OT::$system . '/admin/js/ot-admin.js');?>"></script>
</head>
<body>

    <div id="contain">
      
      <header id="admin-header">
        <h1>Open Translation</h1>
        <p class="admin-website-title"><?php echo $this->escape($this->host);?></p>
      </header>
      
        <?php echo $view_content;?>
    
    </div>

</body>
</html>
