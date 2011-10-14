var options = {};
var selected = '';
var ot = {
  
    // Constants
    C : {
      
      // This is the HTML5 attribute that is used to
      // track individual translatable elements
      translatable_attribute : 'data-translatable-id',
      
      body_translatable_class : 'translatable',
      
      ajax_url : '/ot/lib/ajax.php'
      
    },
    
      // default options
    options : {
        native_locale: 'en_US',
        csrf_token: '',
        translate_type: 'all' // class, selected, all
    },
    
    displayTranslateRequest : function(translatable_id,text) {
      
      var self = this,
          responses = '';
      
      $.ajax({
        url: (self.C.ajax_url + '?ajax_action=fetch_translations_by_native_text_and_translated_code&native_code=' + self.options.native_locale + '&translated_code=' + $('#ot_header select option:selected').val() + '&native_text=' + $('[data-translatable-id='+translatable_id+']').text()),
        type: 'GET',
        success: function(data) {
          console.log(data);
          if (data.success) {
            responses = parseResponses(data);
          }
          else {
            responses = '';
          }
          
          t = $('#ot_translate');
          t.html('');
          t.append('<div class="container"></div>');
          c = $(t.children('.container'));
          c.append('<blockquote class="ot-translate-this">' + text + '</blockquote>');
          console.log(responses);
          responses.length > 0 ? c.append('<ul class="ot-submitted-translations-list">' + responses + '</ul>') : c.append('');
          //c.append('<ul class="responses"><li class="positive">Response1</li><li class="positive">Response2</li><li class="negative">Response3</li></ul>');
          c.append('<p><input type="text" class="ot-add-translation" /></p>')
          c.append('<a href="#" class="ot-submit ot-pill">Submit</a>');
          c.append('<p><a class="cancel close" href="javascript:;">Cancel</a></p>');
          t.fadeIn();
          
          
        }
      });
      
      function parseResponses(data) {
        // console.log(data);
        var outputHTML = '';
          for (i in data.data) {
            outputHTML += ('<li class="ot-list-green">' + data.data[i]['translated_text'] + ' <span>' + (parseInt(data.data[i]['vote_up']) - parseInt(data.data[i]['vote_down'])) + '</span></li>');
          }
        return outputHTML;
      }
      
    },
    
    fetchAvailableLocales : function() {
      
      var self = this;
      
      $.ajax({
        url: (self.C.ajax_url + '?ajax_action=fetch_available_locales'),
        type: 'GET',
        success: function(data) {
          if (data.success) {
            $('#ot_header select').html(getHTMLForReturnedLocales(data));
          }
          else {
            alert('Could not retrieve locales listing.');
          }
        }
      });
      
      function getHTMLForReturnedLocales(data) {
        var outputHTML;
        for (var i in data.data) {
          outputHTML += '<option value="' + i + '"' + (i == 'es_MX' ? ' SELECTED' : '') + '>' + data.data[i] + '</option>';
        }
        return outputHTML;
      }
      
    },
    
    submitTranslation : function(native_text,translated_code,translated_text) {
      
      var self = this;
      
      if (translated_text.length <= 0) {
        alert('You did not enter any translated text.');
        return;
      }
      
      $.ajax({
        url: (self.C.ajax_url),
        type: 'POST',
        data: {
          'ajax_action':'create_translation_entry',
          'page':'home',
          'native_code': self.options.native_locale,
          'native_text':native_text,
          'translated_code':translated_code,
          'translated_text':translated_text
        },
        success : function(data) {
          console.log(data);
          $('#ot_translate').fadeOut();
        }
        
      });
      
    },
    
    clickSubmitTranslation : function() {
      
      var self = this,
          translated_text = '',
          translated_code = '',
          native_text = '';
          
      translated_text = $('#ot_translate .ot-add-translation').val();
      translated_code = $('#ot_header select option:selected').val();
      native_text = $('#ot_translate blockquote').text();
      
      self.submitTranslation(native_text,translated_code,translated_text);
      
    },
    
    init : function($options) {
      
      var self = this;

      // load options
      if ($options.native_locale) {
          self.options.native_locale = $options.native_locale;
      }

      if ($options.csrf_token) {
          self.options.csrf_token = $options.csrf_token;
      }

      if ($options.translate_type) {
          self.options.translate_type = $options.translate_type;
      }
      
      // Add the open-translation box to the body
      $('body').append('<div id="ot_box"><div id="ot_header"><a href="/#">&#215;</a> <span>My language:</span></div>');
      $('body').append('<div id="ot_translate"></div>')
      $('#ot_header').append('<select><option>Français</option><option>Español</option></select><span class="ot-indicate-change-language"></span>')

      
      // Get the available locales, yo!
      self.fetchAvailableLocales();

      $(document).mouseup(function() {
          selected = getSelectedText();
          if (selected != '') {
              if ($('#ot_box').length) {
                  $('#ot_box').show();
              } else {
                  displayTranslateRequest();
              }
          }
      });

      $('.' + self.C.body_translatable_class + ' [' + self.C.translatable_attribute + ']').live('click', function(e) {
          self.displayTranslateRequest($(this).attr(self.C.translatable_attribute),$(this).text());
      });

      $('#ot_translate .ot-submit').live('click', function(e) {
          e.preventDefault();
          self.clickSubmitTranslation();
      });

      $('#ot_translate .close').live('click', function(e) {
          e.preventDefault();
          $('#ot_translate').fadeOut();
      });
      
      function watchHash() {
        $(window).bind('hashchange', function() {
          if (window.location.hash.indexOf('translate') > -1)
            $('body').addClass(self.C.body_translatable_class);
          else
            $('body').removeClass(self.C.body_translatable_class);
        });
        if (window.location.hash.indexOf('translate') > -1)
          $('body').addClass(self.C.body_translatable_class);
        else
          $('body').removeClass(self.C.body_translatable_class);
      }
      watchHash();

      function selectLocale()
      {
         $.ajax({
              url: '../lib/ajax.php',
              type: 'GET',
              data: {
                  ajax_action: 'fetch_available_locales',
                  native_code: self.options.native_code,
                  csrf_token: self.options.csrf_token
              },
              dataType: 'json',
              success: function(result) {
                  if (result.success) {
                      // display
                  } else {
                      // show error
                  }
              }
          });

          $('#ot_box').html('<p>Select Locale:</p>&nbsp;');       
      }

      function displayTranslateRequest()
      {
        $('#ot_box').html('<p>Translate selected text</p>&nbsp;<a href="#" id="ot_confirm">Yes</a>&nbsp;|&nbsp;<a href="#" id="ot_cancel">No</a>');
        $('#ot_box').show();
      }

      function getSelectedText() {
        if (window.getSelection) {
          return window.getSelection();
        } else if (document.selection) {
          return document.selection.createRange().text;
        }
        return '';
      }

      function loadTranslateDialog(locale_code, selected) {
         $.ajax({
              url: '/lib/ajax.php',
              type: 'GET',
              data: {
                  ajax_action: 'fetch_page_translation',
                  page: window.location.pathname,
                  native_code: self.options.native_code,
                  text: selected,
                  csrf_token: self.options.csrf_token
              },
              dataType: 'json',
              success: function(result) {
                  if (result.success) {
                      // display
                  } else {
                      // show error
                  }
              }
          });
      }
  }
}
