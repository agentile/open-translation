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
<?php foreach ($this->translation as $t):?>
<tr>
    <td><?php echo $t['translation_id'];?></td>
    <td><?php echo $t['url'];?></td>
    <td><?php echo $t['native_locale_code'];?></td>
    <td><?php echo $this->escape($t['native_text']);?></td>
    <td><?php echo $t['translated_locale_code'];?></td>
    <td><?php echo $this->escape($t['translated_text']);?></td>
    <td><?php echo long2ip($t['ip']);?></td>
    <td><?php echo (int) $t['vote_up'];?></td>own
    <td><?php echo (int) $t['vote_d'];?></td>
</tr>
<?php endforeach;?>
</table>
