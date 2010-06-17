var csrf_token = '';
var selected = '';
var locale_code = 'en_US';
var ot =
{
    init : function(csrf, native_code) {
        csrf_token = csrf;
        if (native_code) {
            locale_code = native_code;
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
                    locale_code: locale_code,
                    text: selected,
                    csrf_token: csrf_token
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
