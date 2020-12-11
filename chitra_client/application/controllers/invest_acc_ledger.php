<?php

Class Invest_acc_ledger extends Controller {

    var $data;

    //----------------------------------------------------------------------
    function __construct() {
        parent::__construct();
    }
    //----------------------------------------------------------------------
    function check_login_status() {
        if ($this->session->userdata('logged_in_cum') != TRUE) {
            exit(0);
        }
    }
    //----------------------------------------------------------
    function index() {
        $this->check_login_status();
        if ($this->session->userdata('logged_in_cum') == TRUE) {
            $this->display_data($inv_acc_no, $date_filter, $date_filter1);
            $this->load->view('vinvest_acc_ledger', $this->data);
        } else {
            $this->load->view('vlogin');
        }
    }

    //----------------------------------------------------------------------
    function display_data($inv_acc_no, $date_filter, $date_filter1) {
        $this->check_login_status();
        if ($this->session->userdata('logged_in_cum') == TRUE) {
            $this->load->library('table');
            $tmpl = array('table_open' => '<table id="records">', 'heading_row_start' => '<thead><tr>', 'heading_row_end' => '</tr></thead><tbody>');
            $this->table->set_template($tmpl);
            $this->table->set_heading('ID', 'Tran_Date', 'Description', 'Disburse', 'Profit', 'Recovery', 'Balance');
            $from_date = date("Y-m-j", strtotime($date_filter));
            $to_date = date("Y-m-j", strtotime($date_filter1));
            $sql_invest_ledger = "CALL sp_inv_acc_ledger('" . $inv_acc_no . "', '" . $from_date . "', '" . $to_date . "')";

            $query_invest_ledger = $this->db->query($sql_invest_ledger);
            $this->data['table_data'] = $this->table->generate($query_invest_ledger);

            $this->db->close();
            $this->db->initialize();

            $sql = "SELECT t1.id, t1.name, t1.mobile_no FROM (SELECT id, name, mobile_no FROM tbl_client UNION SELECT id, name, mobile_no FROM tbl_member) t1 INNER JOIN tbl_investment_acc t2 ON t2.client_id = t1.id WHERE t2.account_no ='" . $inv_acc_no . "'";
            $query_profile = $this->db->query($sql);
            if($query_profile->num_rows() > 0) {
                $row = $query_profile->row();
                $this->data['profile'] = $row->id . " Name: " . $row->name . " Mobile: " . $row->mobile_no; 
            }

            $this->data['date_range'] = "From: " . $date_filter . " To: " . $date_filter1; 
            $this->load->view('vinvest_acc_ledger', $this->data);
        } else {
            $this->load->view('vlogin');
        }
    }
    //----------------------------------------------------------------------
    function filter_data($inv_acc_no, $date_filter, $date_filter1) {
        $this->check_login_status();
        $from_date = date("Y-m-j", strtotime($date_filter));
        $to_date = date("Y-m-j", strtotime($date_filter1));
        $sql_invest_ledger = "CALL sp_inv_acc_ledger('" . $inv_acc_no . "', '" . $from_date . "', '" . $to_date . "')";
        $query_invest_ledger = $this->db->query($sql_invest_ledger);
        if ($query_invest_ledger->num_rows() > 0) {
            echo json_encode($query_invest_ledger->result());
        } else {
            echo json_encode(array());
        }
    }
    //----------------------------------------------------------------------
    //Functions for showing the invest Account No in the auto complete text box
    function get_inv_acc_no() {
        $this->check_login_status();

        if (isset($_REQUEST['q'])) {
            $sql = "SELECT t1.account_no as id, CONCAT(t1.account_no, '-', t2.name) AS name FROM tbl_invest_acc t1 INNER JOIN (SELECT id, name FROM tbl_client UNION SELECT id, name FROM tbl_member) t2 ON t1.client_id = t2.id WHERE t1.account_no LIKE '%" . strip_slashes($_REQUEST['q']) . "%' OR t2.name LIKE '%" . strip_slashes($_REQUEST['q']) . "%' Order BY t1.open_date DESC";
            $query = $this->db->query($sql);
        }

        if ($query->num_rows() > 0) {
            $result = json_encode($query->result_array());
            $result = '{"results":' . $result . '}';
            echo $result;
        } else {
            echo json_encode(array());
        }
    } 
    //----------------------------------------------------------------------
    //Print invest A/c Ledger in Pdf Format
    function print_pdf_ledger($date_filter, $date_filter1, $inv_acc_no) {
        $this->check_login_status();
        $from_date = date("Y-m-j", strtotime($date_filter));
        $to_date = date("Y-m-j", strtotime($date_filter1));
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set document (meta) information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Chitra Multipurpose Co-operative Society Ltd.');
        $pdf->SetTitle('invest A/c Ledger');
        $pdf->SetSubject('invest A/c Ledger');
        $pdf->SetKeywords('Ledger, invest A/c Ledger');
        $pdf->SetMargins(10, 10, 10, true);
        $pdf->setPrintHeader(False);

        // add a page
        $pdf->AddPage();

        //Create Custom Header
//        $pdf->Image($this->config->item('base_url') . 'images/logo_ara.jpg', 50, 1, 17, 14);
        $pdf->CreateTextBox("Chitra Multipurpose Co-operative Society Ltd.", 0, 0, 170, 10, 17, 'B', 'C');
        $pdf->CreateTextBox("Gazirhat Bazar, Digholia, Khulna, Bangladesh", 0, 10, 170, 7, 10, 'B', 'C');
        $pdf->CreateTextBox("Mobile: 01972-444407, Email: cumsl2010@gmail.com", 0, 15, 170, 7, 10, '', 'C');
        $pdf->Line(20, 25, 200, 25, array('width' => "0.15"));
        $pdf->CreateTextBox("invest A/c Ledger", 0, 25, 170, 10, 17, 'B', 'C');

        // create address box
        $sql_inv_acc = "SELECT t1.account_no, t2.name, t2.address_1 FROM tbl_invest_acc t1 INNER JOIN (SELECT id, name, address_1 FROM tbl_client UNION SELECT id, name, address_1 FROM tbl_member) t2 ON t1.client_id = t2.id WHERE t1.account_no ='" . $inv_acc_no . "'";
        $qry_inv_acc = $this->db->query($sql_inv_acc);
        if ($qry_inv_acc->num_rows() > 0) {
            $result_inv_acc = $qry_inv_acc->row();
            $pdf->CreateTextBox(" ", 0, 30, 80, 10, 10);
            $pdf->CreateTextBox(" ", 0, 30, 80, 10, 10);
            $pdf->CreateTextBox("A/c: " . $result_inv_acc->account_no . "-". $result_inv_acc->name, 0, 30, 80, 10, 10);
            $pdf->CreateTextBox("Address: " . $result_inv_acc->address_1, 0, 35, 80, 10, 10);
            $pdf->CreateTextBox('Date Between: ' . $date_filter, 0, 40, 0, 10, 10, '', 'L');
            $pdf->CreateTextBox(" AND " . $date_filter1, 43, 40, 0, 10, 10, '', 'L');
        }

        $pdf->SetFont("helvetica", "", 8);
        $pdf->SetMargins(30, 10);
        $tbl = "<br/> <br/> <br/><br/>";
        $tbl .= <<<EOD
            <table style="border-collapse:collapse;" cellspacing="0" cellpadding="1" border="1" nobr="true">
               <thead>
                   <tr>
                        <th style="border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;width:65px; text-align:center">ID</th>
                        <th style="border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;width:55px; text-align:center">Tran_Date</th>
                        <th style="border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;width:120px; text-align:center">Description</th>
                        <th style="border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;width:70px; text-align:center">invest</th>
                        <th style="border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;width:70px; text-align:center">Withdrawal</th>
                        <th style="border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;width:70px; text-align:center">Balance</th>
                    </tr> 
                </thead>
                <tbody>
EOD;

        $sql_inv_acc_ledger = "CALL sp_inv_acc_ledger('" . $inv_acc_no . "', '" . $from_date . "', '" . $to_date . "')";
        $qry_inv_acc_ledger = $this->db->query($sql_inv_acc_ledger);
        $arr_ledger = array();
        $arr_ledger_mod = array();
        $arr_tran = array();
        if ($qry_inv_acc_ledger->num_rows() > 0) {
            foreach ($qry_inv_acc_ledger->result() as $row) {
                $cur_id = $row->ID;
                $cur_tran_date = $row->Tran_Date;
                $cur_description = $row->Description;
                $cur_invest = $row->invest;
                $cur_withdrawal = $row->Withdrawal;
                $cur_balance = $row->Balance;
                $tbl .= <<<EOD
                <tr>
                    <td style="border-left-color:#000000;text-align:center;width:65px;">$cur_id</td>
                    <td style="border-left-color:#000000;text-align:center;width:55px;">$cur_tran_date</td>
                    <td style="border-left-color:#000000;text-align:left;width:120px;">$cur_description</td>
                    <td style="border-left-color:#000000;text-align:right;width:70px;">$cur_invest</td>
                    <td style="border-left-color:#000000;text-align:right;width:70px;">$cur_withdrawal</td>
                    <td style="border-left-color:#000000;text-align:right;width:70px;">$cur_balance</td>
                </tr>            
EOD;
            }
            $tbl .= "</tbody></table>";
            $pdf->writeHTML($tbl, true, false, false, false, '');

            $pdf->Output('inv_acc_ledger.pdf', 'I');
        }
    }
    //---------------------------------------------------------------
}

?>