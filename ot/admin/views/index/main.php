<h3>admin home</h3>

<table>
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">URL</th>
            <th scope="col">Native Locale</th>
            <th scope="col">Native Text</th>
            <th scope="col">Translated Code</th>
            <th scope="col">Translated Text</th>
            <th scope="col">IP</th>
            <th scope="col">Voted Up</th>
            <th scope="col">Voted Down</th>
        </tr>
    </thead>
<?php foreach ($this->translations as $t):?>
<tr>
    <td><?php echo $t['translation_id'];?></td>
    <td><?php echo $t['url'];?></td>
    <td><?php echo $t['native_locale_code'];?></td>
    <td><?php echo $this->escape($t['native_text']);?></td>
    <td><?php echo $t['translated_locale_code'];?></td>
    <td><?php echo $this->escape($t['translated_text']);?></td>
    <td><?php echo long2ip($t['ip']);?></td>
    <td><?php echo (int) $t['vote_up'];?></td>
    <td><?php echo (int) $t['vote_d'];?></td>
</tr>
<?php endforeach;?>
</table>

<h4>Manual Entry</h4>
<form method="POST" action="/ot/admin/index.php">
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
