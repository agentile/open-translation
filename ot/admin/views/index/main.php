<div id="contain">
  
  <header id="admin-header">
    <h1>Open Translation</h1>
    <p class="admin-website-title">Website title</p>
  </header>
  
  <section id="admin-translations-table">
    
    <div class="admin-selectors clearfix">
      
      <div class="admin-page-selector-contain">
        <label for="admin-page-selector">View translations on:</label>
        <select id="admin-page-selector">
          <option>Home page</option>
          <option>About page</option>
        </select>
      </div>
      
      <div class="admin-language-selector-contain">
        <label for="admin-language-selector">Language:</label>
        <select id="admin-language-selector">
          <option>French</option>
          <option>Spanish</option>
        </select>
      </div>
  
    </div><!-- .admin-selectors -->
    
    <hr />
    
    <div class="admin-table-headers clearfix">
      <h2 class="header-original">Text in English</h2>
      <h2 class="header-translated">Text in French</h2>
      <h2 class="header-status">Status</h2>
    </div>
    
    <div class="admin-table-items">
      <article class="admin-table-item unapproved clearfix">
        <h3>Hello</h3>
        <h3>Bonjour</h3>
        
        <div class="table-item-status">
          <div class="item-badge badge-positive">5</div>
          <div class="item-approve-button">Approve</div>
          <div class="item-up-votes">
            <span>10 votes:</span>
            <span class="up-votes">Correct</span>
          </div>
          
          <div class="item-down-votes">
            <span>5 votes:</span>
            <span class="down-votes">Incorrect</span>
          </div>
        </div>
      </article>
      
      <article class="admin-table-item unapproved clearfix">
        <h3>Hello</h3>
        <h3>Bonjour</h3>
        
        <div class="table-item-status">
          <div class="item-badge badge-negative">5</div>
          <div class="item-approve-button">Approve</div>
          <div class="item-up-votes">
            <span>10 votes:</span>
            <span class="up-votes">Correct</span>
          </div>
          
          <div class="item-down-votes">
            <span>5 votes:</span>
            <span class="down-votes">Incorrect</span>
          </div>
        </div>
      </article>
      
      <article class="admin-table-item approved clearfix">
        <h3>Hello</h3>
        <h3>Bonjour</h3>
        
        <div class="table-item-status">
          <div class="item-badge badge-negative">5</div>
          <div class="item-approve-button">Reset?</div>
          <div class="item-up-votes">
            <span>10 votes:</span>
            <span class="up-votes">Correct</span>
          </div>
          
          <div class="item-down-votes">
            <span>5 votes:</span>
            <span class="down-votes">Incorrect</span>
          </div>
        </div>
      </article>
    </div><!-- .admin-table-items -->
    
    
    
  </section>
  
</div>



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
