<html>
    <head>
        <title>Chitra Multipurpose Co-operative Society Ltd.</title>
        <?php $this->load->view('jquery_include'); ?>
    </head>

    <body>
        <div id="msgDialog"><p></p></div>

        <div id="login" title="Chitra Multipurpose Co-operative Society Ltd.">
            <form id="login_form" action="" method="POST">
                <table> 
                    <tr>
                        <td> Show Between <input type="text" name="date_filter" id="date_filter" readonly/></td>
                        <td> And <input type="text" name="date_filter1" id="date_filter1" readonly/></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td align="center"><br />
                            <input value="Submit" type="button" id="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            <input value="Change Password" type="button" id="change_passwd">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            <input value="Exit" type="button" id="exit">
                        </td>
                        <td></td>
                    </tr>
                </table>
                <?php echo form_close(); ?>

                <?php echo $table_account; ?>
        </div>

        <script type="text/javascript">
            $(function () {
                //--------------------------------------------------------------
                $('#login').dialog({
                    draggable: false,
                    width: 930,
                    height: 515
                });
                //--------------------------------------------------------------
                $("#date_filter, #date_filter1").datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeYear: true,
                    changeMonth: true
                });
                //--------------------------------------------------------------
                $('#msgDialog').dialog({
                    autoOpen: false,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                            $('#user').focus();
                        }
                    }
                });
                //----------------------------------------------------------------------
                //To show the current date in the Date Field
                function show_date() {
                    var myDate = new Date();
                    var month = myDate.getMonth() + 1;
                    var prettyDate = myDate.getDate() + '-' + month + '-' + myDate.getFullYear();
                    month = "01";
                    var fromDate = "01" + '-' + month + '-' + myDate.getFullYear();
                    $("#date_filter").val(fromDate);
                    $("#date_filter1").val(prettyDate);
                }
                //-----------------------------------------------------------------
                display_data();
                //-----------------------------------------------------------------
                //click Event for Add Button in List Table
                $('#submit').button().click(function () {
                    dataTable_account.fnClearTable(true);
                    $.ajax({
                        url: '<?php echo site_url("acc_balance/get_account_details"); ?>',
                        dataType: 'json',
                        success: function (response) {
                            if (response.length > 0) {
                                for (var i in response) {
                                    dataTable_account.fnAddData([
                                        response[i].Name,
                                        response[i].Address,
                                        response[i].Account_no,
                                        response[i].Acc_type,
                                        response[i].Balance,
                                        response[i].Account_no.substring(0, 1) === "1" ? '<a href=deposit_acc_ledger/display_data/' + response[i].Account_no + '/' + $('#date_filter').val() + '/' + $('#date_filter1').val() + '>' + 'Show</a>' : '<a href=invest_acc_ledger/display_data/' + response[i].Account_no + '/' + $('#date_filter').val() + '/' + $('#date_filter1').val() + '>' + 'Show</a>'
                                    ], false);

                                    dataTable_account.fnDraw(true);
                                    $('#record_account tr').each(function () {
                                        $(this).children('td:eq(0)').css("text-align", "left");
                                        $(this).children('td:eq(1)').css("text-align", "left");
                                        $(this).children('td:eq(2)').css("text-align", "center");
                                        $(this).children('td:eq(3)').css("text-align", "center");
                                        $(this).children('td:eq(4)').css("text-align", "right");
                                        $(this).children('td:eq(5)').css("text-align", "right");
                                    });
                                }
                            } else {

                            }
                        }
                    });
                });
                //-----------------------------------------------------------------
                function display_data() {
                    //$('#records tbody tr').remove();
                    show_date();
                    $(':button').button();
                    if (typeof dataTable_account === 'undefined') {
                        dataTable_account = $('#record_account').dataTable({
                            "bJQueryUI": true,
                            "bPaginate": false,
                            "bLengthChange": true,
                            "bFilter": false,
                            "bAutoWidth": false,
                            "bProcessing": true,
                            "aoColumns": [
                                {sClass: "left", "sWidth": "270px"},
                                {sClass: "left", "sWidth": "250px"},
                                {sClass: "center", "sWidth": "120px"},
                                {sClass: "center", "sWidth": "120px"},
                                {sClass: "right", "sWidth": "140px"},
                                {sClass: "center", "sWidth": "140px"}
                            ]
                        });
                    }
                }
                //-----------------------------------------------------------------
                $('#exit').button().click(function () {
                    window.location.href = "<?php echo site_url('login/exit_all'); ?>";
                });
                //-----------------------------------------------------------------
                $('#change_passwd').button().click(function () {
                    window.location.href = "<?php echo site_url('change_passwd'); ?>";
                });
                //-----------------------------------------------------------------
            });
        </script>
    </body>

</html>
