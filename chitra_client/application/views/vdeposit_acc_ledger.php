<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $this->session->userdata('prj_name_cum'); ?></title>
        <?php $this->load->view('jquery_include'); ?>
    </head>

    <body>
        <div id="msgDialog"><p></p></div>

        <div id="view">
            <p class="top_heading">Deposit Account Ledger</p>
            <p class="right" id="cur_date"></p>
            <table class="left">
                <tr>
                    <td> <?= $date_range ?></td>
                </tr>
                <tr>
                    <td> <?= $profile ?></td>
                </tr>
                <tr>
                    <td> <input type="button" name="Exit" id="close" value="Exit"/></td>
                    <!--<td><input type="button" name="pdf_ledger" id="pdf_ledger" value="Print Pdf"/></td>-->
                </tr>
            </table>
            <?php echo $table_data; ?>
        </div>
        <script type="text/javascript">
            $(function() {
                //---------------------------------------------------------------
                $('#view ').tabs({
                    fx: {height: 'toggle', opacity: 'toggle'}

                }).css('width', '1000px').css('margin', '0 auto');

                $("#date_filter, #date_filter1").datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeYear: true,
                    changeMonth: true
                });
                //----------------------------------------------------------------------

                display_data();

                //----------------------------------------------------------------------
                //To show the current date in the Date Field
                function show_date(){
                    var myDate = new Date();
                    var month = myDate.getMonth() + 1;
                    var prettyDate =  myDate.getDate() + '-' + month + '-' + myDate.getFullYear();
                    month = "01";
                    var fromDate =  "01" + '-' + month + '-' + myDate.getFullYear();
                    $("#date_filter").val(fromDate);
                    $("#date_filter1").val(prettyDate);
                }
                //--------------------------------------------------------------

                function display_data(){
                    show_date();
                    if(typeof dataTable == 'undefined') {
                        dataTable = $('#records').dataTable({
                            "bJQueryUI": true,
                            "bPaginate": false,
                            "bInfo": false,
                            "bLengthChange": true,
                            "bAutoWidth": false,
                            "bFilter": false,
                            "bSort": false,
                            "aoColumns":[
                                {sClass:"left", "sWidth":"200px"},
                                {sClass:"left", "sWidth":"120px"},
                                {sClass:"left", "sWidth":"330px"},
                                {sClass:"left", "sWidth":"150px"},
                                {sClass:"left", "sWidth":"150px"},
                                {sClass:"right", "sWidth":"180px"}
                            ]
                        });

                    }
                    $('#records tr').each(function() {
                        $(this).children('td:eq(0)').css("text-align", "left");
                        $(this).children('td:eq(1)').css("text-align", "center");
                        $(this).children('td:eq(2)').css("text-align", "left");
                        $(this).children('td:eq(3)').css("text-align", "right");
                        $(this).children('td:eq(4)').css("text-align", "right");
                        $(this).children('td:eq(5)').css("text-align", "right");
                    });
                    var myDate = new Date();
                    var month = myDate.getMonth() + 1;
                    var prettyDate =  myDate.getDate() + '-' + month + '-' + myDate.getFullYear();
                    $("#cur_date").text("Print Date: " + prettyDate);
                }
                //----------------------------------------------------------------------
                $('#close').button().click(function() {
                    window.location.href = "<?php echo site_url("login/load_main"); ?>";
                });

                //------------------------------------------------------------------------
                //To filter for showing data in the view tab
                $("#filter").button().click(function(){
                    var is_valid_test = false;
                    is_valid_test = true;

                    if (is_valid_test == true) {
                        $("#filter").button("disable");
                        dataTable.fnClearTable(true);
                        $('#records tbody tr').remove();
                        $acc_no = $('#dep_acc_no_hidden').val();

                        $.ajax({
                            url: '<?php echo site_url("deposit_acc_ledger/filter_data"); ?>' + '/' + $('#date_filter').val() + '/' + $('#date_filter1').val() + '/' + $acc_no,
                            dataType: 'json',
                            success: function(response){
                                if(response.length > 0){
                                    var r = new Array(), j = -1;
                                    for (var i in response){
                                        r[++j] ='<tr><td>';
                                        r[++j] = response[i].ID;
                                        r[++j] = '</td><td>';
                                        r[++j] = response[i].Tran_Date;
                                        r[++j] = '</td><td>';
                                        r[++j] = response[i].Description;
                                        r[++j] = '</td><td>';
                                        r[++j] = response[i].Deposit;
                                        r[++j] = '</td><td>';
                                        r[++j] = response[i].Withdrawal;
                                        r[++j] = '</td><td>';
                                        r[++j] = response[i].Balance;
                                        r[++j] = '</td></tr>';
                                    }
                                    $('#records tbody').html(r.join(''));
                                    $('#records tr').each(function() {
                                        $(this).children('td:eq(0)').css("text-align", "left");
                                        $(this).children('td:eq(1)').css("text-align", "center");
                                        $(this).children('td:eq(2)').css("text-align", "left");
                                        $(this).children('td:eq(3)').css("text-align", "right");
                                        $(this).children('td:eq(4)').css("text-align", "right");
                                        $(this).children('td:eq(5)').css("text-align", "right");
                                    });
                                    $("#filter").button("enable");
                                } else{
                                    $("#filter").button("enable");
                                }
                            }
                        });
                    } else {
                        alert("Select Correct Deposit Account");
                    }
                });
                //--------------------------------------------------------------------------------
				$("#pdf_ledger").button().click(function(){
					if($('#dep_acc_no_hidden').val().length > 1){
                        window.open('<?php echo site_url("deposit_acc_ledger/print_pdf_ledger"); ?>' + '/' + $('#date_filter').val() + '/' + $('#date_filter1').val() + '/' + $('#dep_acc_no_hidden').val(), "Deposit A/c Ledger");
					} else {
						alert("Invalid A/c");
					}
				});
                //---------------------------------------------------------------------------
            })
        </script>
    </body>
</html>