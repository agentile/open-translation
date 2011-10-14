<h4>Manual Entry</h4>
<form method="POST" action="/ot/admin/new.php">
    <label>URL</label><br/>
    <input type="text" name="url"/><br/>
    <label>Native Locale Code</label><br/>
    <select name="native_code">
        <?php foreach ($this->locales as $k => $l):?>
        <option value="<?php echo $k;?>"><?php echo $l;?></option>
        <?php endforeach;?>
    </select><br/>
    
    <label>Original Text</label><br/>
    <textarea name="native_text"></textarea><br/>
    
    <label>Translate Locale Code</label><br/>
    <select name="translated_code">
        <?php foreach ($this->locales as $k => $l):?>
        <option value="<?php echo $k;?>"><?php echo $l;?></option>
        <?php endforeach;?>
    </select><br/>
    
    <label>Translated Text</label><br/>
    <textarea name="translated_text"></textarea><br/>
    <input type="submit" name="submit" value="submit">
</form>
