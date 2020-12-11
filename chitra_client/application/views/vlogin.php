<html>
    <head>
        <title>Chitra Multipurpose Co-operative Society Ltd.</title>
        <script type="text/JavaScript" src="<?php echo $this->config->item('base_url') . 'js/jquery-1.4.4.min.js' ?>"> </script>
        <script type="text/JavaScript" src="<?php echo $this->config->item('base_url') . 'js/jquery-ui-1.8.7.custom.min.js' ?>"> </script>
        <script type="text/JavaScript" src="<?php echo $this->config->item('base_url').'js/jquery.dataTables.min.js'?>"> </script>
        <script type="text/JavaScript" src="<?php echo $this->config->item('base_url').'js/jquery.json-2.2.min.js'?>"> </script>
        <link href="<?php echo $this->config->item('base_url') . 'css/cupertino/jquery-ui-1.8.10.custom.css' ?>" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <div id="msgDialog"><p></p></div>

        <div id="login" title="Chitra Multipurpose Co-operative Society Ltd.">
            <form id="login_form" action="" method="POST">
                <table> 
                    <tr>
                        <td><label for="mobile">Mobile No</label></td>
                        <td><input name="mobile" id="mobile" type="text"/></td>
                    </tr>
                    <tr>
                        <td><label for="passwd">Password </label></td>
                        <td><input name="passwd" id="passwd" type="password"/></td>
                    </tr> 
                    <tr>
                        <td><hr></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td align="center"><br /><input value="submit" type="button" id="submit"></td>
                        <td></td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
        </div>


    <script type="text/javascript">
        $(function() {
            $('#login').dialog({
                draggable:false,
                    width: 500,
                    height: 220
            });

            //--------------------------------------------------------------
            $('#msgDialog').dialog({
                autoOpen:false,

                buttons: {
                'OK': function() {
                    $(this).dialog('close');
                    $('#user').focus();
                }
            }
            });
            //-----------------------------------------------------------------
//            $('#submit').button().click(function() {
//                $.post('<?php echo site_url("login/checkLogin"); ?>', $('#login_form').serialize(), function(data) {
//                    if(data == true) {
//                        window.location.href = "<?php echo site_url('login/load_account'); ?>";
//                    } else if(data == 'passwd_change') {
//                        window.location.href = "<?php echo site_url('change_passwd'); ?>";
//                    } else {
//                        $('#msgDialog > p').html("Enter Correct User and Password");
//                        $('#msgDialog').dialog('option', 'title', 'Warning').dialog('open');
//                    }
//                });
//            });

            //-----------------------------------------------------------------
            $('#submit').button().click(function () {
                var jsonStr = [];
                jsonStr = {"mobile": $('#mobile').val(),
                          "passwd": $('#passwd').val()};
                $.ajax({
                    url: '<?php echo site_url("login/checkLogin"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {'jsarray': $.toJSON(jsonStr)},
                    success: function (response) {
                        if (response === 1) {
                            window.location.href = "<?php echo site_url('acc_balance'); ?>";
                        } else {
                            $('#msgDialog > p').html("Enter Correct Mobile and Password");
                            $('#msgDialog').dialog('option', 'title', 'Warning').dialog('open');
                        }
                    }
                });
            });
            //-----------------------------------------------------------------
        });
    </script>
</body>

</html>
