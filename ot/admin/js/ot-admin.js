var csrf_token = '';
var ot_admin =
{
    chosen :
    {
        init : function() {
            $(document).ready(function() {
                // set chosen select boxes
                $(".chzn-select").chosen();
            });
        }
    },
    index :
    {
        main:
        {
            init : function()
            {
                $(document).ready(function() {
                    $('#admin-page-selector').change(function() {
                        $('#nav-form').submit();
                    });
                    
                    $('#admin-language-selector').change(function() {
                        $('#nav-form').submit();
                    });
                    
                    $('.item-approve-button').click(function() {
                        if ($(this).parents('.admin-table-item').hasClass('unapproved')) {
                            var status = 1, current_class = 'unapproved', to_class = 'approved';
                        } else {
                            var status = 0, current_class = 'approved', to_class = 'unapproved';
                        }
                        var button = $(this);
                        $.ajax({
                            url: '/ot/lib/ajax.php',
                            type: 'POST',
                            data: {
                              'ajax_action':'set_entry_status',
                              'tid': $(this).data('tid'),
                              'status' : status
                            },
                            success : function(data) {
                                button.parents('.admin-table-item').removeClass(current_class).addClass(to_class);
                            }
                        });
                    });
                });
            }
        }
    }
}


