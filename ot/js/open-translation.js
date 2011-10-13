var options = {};
var selected = '';
var ot =
{
  
    // Constants
    C : {
      
      // This is the HTML5 attribute that is used to
      // track individual translatable elements
      translatable_attribute : 'data-translatable-id',
      
      body_translatable_class : 'translatable'
      
    },
    
    init : function($options) {
      
      var self = this;
        
        // default options
        options = {
            native_locale: 'en_US',
            csrf_token: '',
            translate_type: 'all' // class, selected, all
        };

        // load options
        if ($options.native_locale) {
            options.native_locale = $options.native_locale;
        }

        if ($options.csrf_token) {
            options.csrf_token = $options.csrf_token;
        }

        if ($options.translate_type) {
            options.translate_type = $options.translate_type;
        }
        
        // Add the open-translation box to the body
        $('body').append('<div id="ot_box"><div id="ot_spinner"></div><div id="ot_header"><span>Translating to:</span></div>');
        $('#ot_header').append('<select><option>Français</option><option>Español</option>')
        var spinner_opts = {
          lines: 10, // The number of lines to draw
          length: 0, // The length of each line
          width: 8, // The line thickness
          radius: 0, // The radius of the inner circle
          color: '#fff', // #rgb or #rrggbb
          speed: 1.1, // Rounds per second
          trail: 42, // Afterglow percentage
          shadow: false // Whether to render a shadow
        };
        var spinner = new Spinner(spinner_opts).spin();
        var target = document.getElementById('ot_spinner');
        target.appendChild(spinner.el)

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

        $('[' + self.C.translatable_attribute + ']').live('click', function(e) {
            selected = $(this).text();
            displayTranslateRequest();
        });

        $('#ot_confirm').live('click', function(e) {
            e.preventDefault();
            selectLocale();
        });

        $('#ot_cancel').live('click', function(e) {
            e.preventDefault();
            $(this).parent('div').hide();
        });
        
        function watchHash() {
          $(window).bind('hashchange', function() {
            if (window.hash.indexOf('translate') > -1)
              $('body').addClass(C.body_translatable_class);
          });
        }
        watchHash();

        function selectLocale()
        {
           $.ajax({
                url: '../lib/ajax.php',
                type: 'GET',
                data: {
                    ajax_action: 'fetch_available_locales',
                    native_code: options.native_code,
                    csrf_token: options.csrf_token
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
                    native_code: options.native_code,
                    text: selected,
                    csrf_token: options.csrf_token
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
