<div id="contain">
  
  <header id="admin-header">
    <h1>Open Translation</h1>
    <p class="admin-website-title"><?php echo $this->escape($this->host);?></p>
  </header>
  
  <section id="admin-translations-table">
    
    <div class="admin-selectors clearfix">
      
      <form method="GET" action="/ot/admin/index.php" id="nav-form">
          <div class="admin-page-selector-contain">
            <label for="admin-page-selector">View translations on:</label>
            <select id="admin-page-selector" name="url">
              <?php foreach($this->pages as $page):?>
              <option value="<?php echo $page['url'];?>"<?php echo ($this->selected_page == $page['url']) ? ' selected="selected"' : '';?>><?php echo $page['url'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          
          <div class="admin-language-selector-contain">
            <label for="admin-language-selector">Language:</label>
            <select id="admin-language-selector" name="translated_code">
                <?php foreach ($this->locales as $k => $l):?>
                <option value="<?php echo $k;?>"<?php echo ($this->selected_translated_code == $k) ? ' selected="selected"' : '';?>><?php echo $l;?></option>
                <?php endforeach;?>
            </select>
          </div>
       </form>
    </div><!-- .admin-selectors -->
    
    <hr />
    
    <div class="admin-table-headers clearfix">
      <h2 class="header-original">Text in <?php echo $this->locales[$this->selected_native_code];?></h2>
      <h2 class="header-translated">Text in <?php echo (isset($this->locales[$this->selected_translated_code])) ? $this->locales[$this->selected_translated_code] : 'ALL' ;?></h2>
      <h2 class="header-status">Status</h2>
    </div>
    
    <div class="admin-table-items">
      <?php if (!empty($this->translations)):?>
          <?php foreach ($this->translations as $t):?>
          <?php $status = ($t['status'] == 0) ? 'unapproved' : 'approved';?>
          <?php $difference = ($t['vote_up'] - $t['vote_down']); 
                $result = ($difference < 0) ? 'negative' : 'positive';
                $abs_diff = abs($difference);?>
    
          <article class="admin-table-item <?php echo $status;?> clearfix">
            <h3><?php echo $this->escape($t['native_text']);?></h3>
            <h3><?php echo $this->escape($t['translated_text']);?></h3>
            
            <div class="table-item-status">
                
              <div class="item-badge badge-<?php echo $result;?>"><?php echo $abs_diff;?></div>
              <div class="item-approve-button" data-tid="<?php echo $t['translation_id'];?>"><?php echo ($status == 'approved') ? 'Reset?' : 'Approve';?></div>
              <div class="item-up-votes">
                <span><?php echo $t['vote_up'];?> votes:</span>
                <span class="up-votes">Correct</span>
              </div>
              
              <div class="item-down-votes">
                <span><?php echo $t['vote_down'];?> votes:</span>
                <span class="down-votes">Incorrect</span>
              </div>
            </div>
          </article>
          <?php endforeach;?>
      <?php else:?>
        <p>No translations found.</p>
      <?php endif;?>

    </div><!-- .admin-table-items -->
    
  </section>
  
</div>


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

<script type="text/javascript">
ot_admin.index.main.init();
</script>
