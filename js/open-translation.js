var options = {};
var selected = '';
var ot =
{
    init : function($options) {
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

        $('.ot_translatable').click(function(e) {
            selected = $('.ot_translatable').text();
            displayTranslateRequest();
        });

        $('#ot_confirm').live('click', function(e) {
            e.preventDefault();
            $(this).parent('div').hide();
            loadTranslateDialog(locale_code, selected);
        });

        $('#ot_cancel').live('click', function(e) {
            e.preventDefault();
            $(this).parent('div').hide();
        });

        function displayTranslateRequest()
        {
            $('body').prepend('<div id="ot_box"><p>Translate selected text</p><a href="#" id="ot_confirm">Yes</a>&nbsp;|&nbsp;<a href="#" id="ot_cancel">No</a></div>');       
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
                    ajax_action: 'fetch_translation',
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
