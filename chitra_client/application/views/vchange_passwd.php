<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $this->session->userdata('prj_name_cum'); ?></title>
        <?php $this->load->view('jquery_include'); ?>
    </head>

    <body>

        <div id="msgDialog"><p></p></div>

        <div id="tabs">
            <ul>
                <li><a href="#view">Enter Details</a></li>
                <!--				<li><a href="#entryform">Enter New Product Group</a></li>-->
            </ul>

            <div align="center" id="entryform">
                <form action="" method="POST">

                    <table> 
                        <tr>
                            <td><label for="cur_passwd">Current Password</label></td>
                            <td><input name="cur_passwd" id="cur_passwd" type="password" maxlength="30" size="20" /></td>
                        </tr>

                        <tr>
                            <td><label for="new_passwd">New Password</label></td>
                            <td><input name="new_passwd" id="new_passwd" type="password" maxlength="30" size="20" /></td>
                        </tr>

                        <tr>
                            <td><label for="confirm_passwd">Confirm Password</label></td>
                            <td><input name="confirm_passwd" id="confirm_passwd" type="password" maxlength="30" size="20" /></td>
                        </tr>


                    </table>
                    <table>
                        <tr>
                            <td> <input type="button" name="entrySubmit" id="entrySubmit" value="Save"/></td>
                            <td><input type="button" name="clear" id="clear" value="Clear"/></td>
                            <td> <input type="button" name="Exit" id="exit" value="Exit"/></td>
                        </tr>
                    </table>
                </form>		
            </div>

        </div> <!-- end tabs  -->

        <script type="text/javascript">
            var updateUrl = 'change_passwd/update_data',
                    deleteUrl = 'change_passwd/delete_data',
                    delHref,
                    updateHref,
                    updateId,
                    update_position,
                    dataTable;

            $(function () {
                //---------------------------------------------------------------
                $('#tabs ').tabs({
                    fx: {height: 'toggle', opacity: 'toggle'}

                }).css('width', '500px').css('margin', '0 auto');

                //---------------------------------------------------------------
                display_data();
                //---------------------------------------------------------------

                $('#msgDialog').dialog({
                    autoOpen: false,

                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                            $('#cur_passwd').focus();
                        }
                    }
                });

                //-----------------------------------------------------------------

                $('#entrySubmit').button().click(function () {
                    $("#entrySubmit").button("disable");
                    var jsonStr = [];
                    jsonStr = {"cur_passwd": $('#cur_passwd').val(),
                        "new_passwd": $('#new_passwd').val(),
                        "confirm_passwd": $('#confirm_passwd').val()};
                    $.ajax({
                        url: '<?php echo site_url("change_passwd/save_data"); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {'jsarray': $.toJSON(jsonStr)},

                        success: function (response) {
                            if (response.valid == "Success") {
                                $('#msgDialog > p').html("Password Changed Successfully");
                                $('#msgDialog').dialog('option', 'title', 'Success').dialog('open');
                                clear_field();
                            } else {
                                $('#msgDialog > p').html(response.valid);
                                $('#msgDialog').dialog('option', 'title', 'Warning').dialog('open');
                                $("#entrySubmit").button("enable");
                            }
                        }
                    });
                });
                //----------------------------------------------------------------------

                $('#clear').button().click(function () {
                    clear_field();

                });

                //-----------------------------------------------------------------------

                $('#exit').button().click(function () {
                    window.location.href = "<?php echo site_url('acc_balance'); ?>";
                });

                //-----------------------------------------------------------------------

                $('#close').button().click(function () {
                    window.location.href = "<?php echo site_url('acc_balance'); ?>";
                });

                //----------------------------------------------------------------------

                function clear_field() {
                    //to clear the current form field
                    $(':password').val('');
                    $("#entrySubmit").button("enable");
                }

                //----------------------------------------------------------------------
                function display_data() {
                    clear_field();
                }
                //----------------------------------------------------------------------
            })
        </script>
    </body>
</html>