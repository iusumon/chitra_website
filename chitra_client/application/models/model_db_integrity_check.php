<?php

class Model_db_integrity_check extends Model {

    //----------------------------------------------------------
    function __construct() {
        parent::Model();
    }

    //----------------------------------------------------------
    function check_integrity($child_table, $field_name, $value, $exclude_update_id="", $exclude_update_val="") {
        $this->db->where($field_name, $value, TRUE);
        if(strlen($exclude_update_id)>0 && strlen($exclude_update_val)>0) {
            $this->db->where("$exclude_update_id !=", $exclude_update_val);
        }
        $this->db->from($child_table);
        $count = $this->db->count_all_results();
        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    //----------------------------------------------------------
    function check_privilege($field_privilege) {
        $sql = "SELECT $field_privilege AS value FROM tbl_tran_privilege WHERE user_id ='" . $this->session->userdata('user_id_cum') . "'";
        $query = $this->db->query($sql);
        $row = $query->row();
        if ($row->value == 0) {
            return "failed";
        }
    }

    //----------------------------------------------------------
    function check_privilege_rpt($field_privilege) {
        $sql = "SELECT $field_privilege AS value FROM tbl_rpt_privilege WHERE user_id ='" . $this->session->userdata('user_id_cum') . "'";
        $query = $this->db->query($sql);
        $row = $query->row();
        if ($row->value == 0) {
            return "failed";
        }
    }
    //----------------------------------------------------------
    function check_privilege_save($field_privilege, $user_date) {

        if ($field_privilege == "cust_invoice") {
            $tbl_name = "tblOrder";
        } else if ($field_privilege == "cust_payment") {
            $tbl_name = "tblCustPayment";
        } else if ($field_privilege == "vendor_invoice") {
            $tbl_name = "tblVendorPurchase";
        } else if ($field_privilege == "vendor_payment") {
            $tbl_name = "tblVendorPayment";
        } else if ($field_privilege == "bank_deposit") {
            $tbl_name = "tblMoneyDeposit";
        } else if ($field_privilege == "bank_withdrawal") {
            $tbl_name = "tblMoneyWithDrawl";
        } else if ($field_privilege == "daily_expense") {
            $tbl_name = "tblExpenses";
        } else if ($field_privilege == "other_income") {
            $tbl_name = "tblStudentPayment";
        } else if ($field_privilege == "capital_invest") {
            $tbl_name = "tblCapitalInvestment";
        } else if ($field_privilege == "salary_account") {
            $tbl_name = "tblSalaryAmount";
        } else if ($field_privilege == "salary_payment") {
            $tbl_name = "tblSalary";
        } else if ($field_privilege == "loan_receipt") {
            $tbl_name = "tblCreditorPayment";
        } else if ($field_privilege == "loan_payment") {
            $tbl_name = "tblCredit";
        }

        //For Checking User Privilege on Saving Data
        $sql = "SELECT $field_privilege AS value FROM tblPrivilege WHERE user_id ='" . $this->session->userdata('user_id_cum') . "'";
        $query = $this->db->query($sql);
        $row = $query->row();
        if ($row->value == 0) {
            return "failed";
        }
        //for previous Date edit permission
        $sql = "SELECT MAX(Date) AS max_date FROM $tbl_name";
        $query = $this->db->query($sql);

        $row = $query->row();
        if (strtotime($user_date) < strtotime($row->max_date) AND $this->session->userdata('user_id_cum') != 'U0001') {
            return "failed";
        }
    }

    //----------------------------------------------------------
    function check_privilege_update() {
        if ($this->session->userdata('user_id_cum') != 'U0001') {
            return "failed";
        }
    }
    //----------------------------------------------------------
//    function check_privilege_update($field_privilege, $inv_id, $user_date) {
//
//        if ($field_privilege == "daily_coll") {
//            $tbl_name = "tbl_daily_coll";
//        } else if ($field_privilege == "cust_payment") {
//            $tbl_name = "tblCustPayment";
//        } else if ($field_privilege == "vendor_invoice") {
//            $tbl_name = "tblVendorPurchase";
//        } else if ($field_privilege == "vendor_payment") {
//            $tbl_name = "tblVendorPayment";
//        } else if ($field_privilege == "bank_deposit") {
//            $tbl_name = "tblMoneyDeposit";
//        } else if ($field_privilege == "bank_withdrawal") {
//            $tbl_name = "tblMoneyWithDrawl";
//        } else if ($field_privilege == "daily_expense") {
//            $tbl_name = "tblExpenses";
//        } else if ($field_privilege == "other_income") {
//            $tbl_name = "tblStudentPayment";
//        } else if ($field_privilege == "capital_invest") {
//            $tbl_name = "tblCapitalInvestment";
//        } else if ($field_privilege == "salary_account") {
//            $tbl_name = "tblSalaryAmount";
//        } else if ($field_privilege == "salary_payment") {
//            $tbl_name = "tblSalary";
//        } else if ($field_privilege == "loan_receipt") {
//            $tbl_name = "tblCreditorPayment";
//        } else if ($field_privilege == "loan_payment") {
//            $tbl_name = "tblCredit";
//        }
//
//        $sql = "SELECT $field_privilege AS value FROM tbl_tran_privilege WHERE user_id ='" . $this->session->userdata('user_id_cum') . "'";
//        $query = $this->db->query($sql);
//        $row = $query->row();
//        if ($row->value == 0) {
//            return "failed";
//        }
//        //for previous Date edit permission
//        if (strlen($inv_id) > 0) {
//            $sql = "SELECT date AS inv_date FROM $tbl_name WHERE id ='" . $inv_id . "'";
//            $query = $this->db->query($sql);
//            if ($query->num_rows() > 0) {
//                $row = $query->row();
//                $inv_date = $row->inv_date;
//                $sql = "SELECT MAX(date) AS max_date FROM $tbl_name";
//                $query = $this->db->query($sql);
//
//                $row = $query->row();
////                if(strtotime($inv_date) < strtotime($row->max_date) AND $this->session->userdata('user_id_cum') != 'U0001') {
////                    return "failed";  
////                } elseif(strlen($user_date) > 0 AND strtotime($user_date) < strtotime($row->max_date) AND $this->session->userdata('user_id_cum') != 'U0001') {
////                    return "failed";  
////                }
//                if ($this->session->userdata('user_id_cum') != 'U0001') {
//                    return "failed";
//                }
//            }
//        }
//    }

    //----------------------------------------------------------
    function check_privilege_delete() {
        if ($this->session->userdata('user_id_cum') != 'U0001') {
            return "failed";
        }
    }
    //----------------------------------------------------------
//    function check_privilege_delete($field_privilege, $inv_id) {
//
//        if ($field_privilege == "daily_coll") {
//            $tbl_name = "tbl_daily_coll";
//        } else if ($field_privilege == "cust_payment") {
//            $tbl_name = "tblCustPayment";
//        } else if ($field_privilege == "vendor_invoice") {
//            $tbl_name = "tblVendorPurchase";
//        } else if ($field_privilege == "vendor_payment") {
//            $tbl_name = "tblVendorPayment";
//        } else if ($field_privilege == "bank_deposit") {
//            $tbl_name = "tblMoneyDeposit";
//        } else if ($field_privilege == "bank_withdrawal") {
//            $tbl_name = "tblMoneyWithDrawl";
//        } else if ($field_privilege == "daily_expense") {
//            $tbl_name = "tblExpenses";
//        } else if ($field_privilege == "other_income") {
//            $tbl_name = "tblStudentPayment";
//        } else if ($field_privilege == "capital_invest") {
//            $tbl_name = "tblCapitalInvestment";
//        } else if ($field_privilege == "salary_account") {
//            $tbl_name = "tblSalaryAmount";
//        } else if ($field_privilege == "salary_payment") {
//            $tbl_name = "tblSalary";
//        } else if ($field_privilege == "loan_receipt") {
//            $tbl_name = "tblCreditorPayment";
//        } else if ($field_privilege == "loan_payment") {
//            $tbl_name = "tblCredit";
//        }
//
//        $sql = "SELECT $field_privilege AS value FROM tbl_tran_privilege WHERE user_id ='" . $this->session->userdata('user_id_cum') . "'";
//        $query = $this->db->query($sql);
//        $row = $query->row();
//        if ($row->value == 0) {
//            return "failed";
//        }
//
//        if ($this->session->userdata('user_id_cum') != 'U0001') {
//            return "failed";
//        }
//    }
    //----------------------------------------------------------
    function prevent_previous_data_edit($tbl_name, $date_user) {
        $sql = "SELECT MAX(Date) AS max_date FROM $tbl_name";
        $query = $this->db->query($sql);
        $row = $query->row();
        if (strtotime($date_user) < strtotime($row->max_date) AND $this->session->userdata('user_id_cum') != 'U0001') {
            return false;
//            echo 'You have no permission!';
//            exit (0);
        } else {
            return true;
        }
    }

    //----------------------------------------------------------
    function send_mail() {
        $transaction_detail = "";
        $sql = "SELECT t1.ID, t1.CustomerName AS Particulars, t1.Date, (t1.Amount - t1.Discount + t1.ShippingCost) AS Amount, t2.Payment, 'Sales' AS Tran_Type FROM tblOrder t1 INNER JOIN tblCustPayment t2 ON t1.ID = t2.OrderID WHERE t1.InvoiceType = 'SI' AND t1.Date = CURDATE() " .
                "UNION SELECT t1.ID, t1.CustomerName AS Particulars, t1.Date, (t1.Amount - t1.Discount + t1.ShippingCost) AS Amount, t2.Payment, 'Sales Return' FROM tblOrder t1 INNER JOIN tblCustPayment t2 ON t1.ID = t2.OrderID WHERE t1.InvoiceType = 'SRI' AND t1.Date = CURDATE() " .
                "UNION SELECT ID,  CustomerName, Date, 0, Payment, 'Customer_Receipt' From tblCustPayment WHERE OrderID ='NA' AND InvoiceType = 'SI' AND Date = CURDATE() " .
                "UNION SELECT ID,  CustomerName, Date, Payment, 0, 'Customer_Payment' From tblCustPayment WHERE OrderID ='NA' AND InvoiceType = 'SRI' AND Date = CURDATE()" .
                "UNION SELECT ID,  CustomerName, Date, 0, Payment, 'Customer_Cheque_Receipt' From tblCustPayment WHERE OrderID ='NA' AND InvoiceType = 'CR' AND Date = CURDATE()" .
                "UNION SELECT ID,  CustomerName, Date, Payment, 0, 'Customer_Cheque_Payment' From tblCustPayment WHERE OrderID ='NA' AND InvoiceType = 'CP' AND Date = CURDATE()" .
                "UNION SELECT ID,  CustomerName, Date, 0, Payment, 'Customer_Adjust_Receipt' From tblCustPayment WHERE OrderID ='NA' AND InvoiceType = 'AR' AND Date = CURDATE()" .
                "UNION SELECT ID,  CustomerName, Date, Payment, 0, 'Customer_Adjust_Payment' From tblCustPayment WHERE OrderID ='NA' AND InvoiceType = 'AP' AND Date = CURDATE()";
        $transaction_detail = (string) $this->ascii_sql_result($sql);

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'zonekhulna@gmail.com',
            'smtp_pass' => 'khulnazone123'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n"); /* for some reason it is needed */

        $this->email->from('zonekhulna@gmail.com', 'ESMS-Mail Notification System');
        $this->email->to('zonekhulna@gmail.com');
        $this->email->subject('ESMS Transactions Update');
        $this->email->message($transaction_detail);
        if (strlen($transaction_detail) > 0) {
            if ($this->email->send()) {
                //echo 'Your email was sent';
            } else {
                //show_error($this->email->print_debugger());
                //echo "Mail Delivery Failed";
            }
        }
    }

    //----------------------------------------------------------
    function ascii_sql_result($sql) {

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }

            $keys = array_keys(end($data));

            # calculate optimal width
            $wid = array_map('strlen', $keys);
            foreach ($data as $row) {
                foreach (array_values($row) as $k => $v)
                    $wid[$k] = max($wid[$k], strlen($v));
            }

            # build format and separator strings
            foreach ($wid as $k => $v) {
                $fmt[$k] = "%-{$v}s";
                $sep[$k] = str_repeat('-', $v);
            }
            $fmt = '| ' . implode(' | ', $fmt) . ' |';
            $sep = '+-' . implode('-+-', $sep) . '-+';

            # create header
            $buf = array($sep, vsprintf($fmt, $keys), $sep);

            # print data
            foreach ($data as $row) {
                $buf[] = vsprintf($fmt, $row);
                $buf[] = $sep;
            }

            # finis
            return implode("\n", $buf);
        } else {
            return "";
        }
    }

    //-----------------------------------------------------------------------
    function getServerAddress() {
//        if (isset($_SERVER["SERVER_ADDR"]))
//            return $_SERVER["SERVER_ADDR"];
//        else {
//            // Running CLI
//            if (stristr(PHP_OS, 'WIN')) {
//                //  Rather hacky way to handle windows servers
//                exec('ipconfig /all', $catch);
//                foreach ($catch as $line) {
//                    if (eregi('IP Address', $line)) {
//                        // Have seen exec return "multi-line" content, so another hack.
//                        if (count($lineCount = split(':', $line)) == 1) {
//                            list($t, $ip) = split(':', $line);
//                            $ip = trim($ip);
//                        } else {
//                            $parts = explode('IP Address', $line);
//                            $parts = explode('Subnet Mask', $parts[1]);
//                            $parts = explode(': ', $parts[0]);
//                            $ip = trim($parts[1]);
//                        }
//                        if (ip2long($ip > 0)) {
//                            echo 'IP is ' . $ip . "\n";
//                            return $ip;
//                        } else
//                    }
//                }
//            } else {
//                $ifconfig = shell_exec('/sbin/ifconfig eth0');
//                preg_match('/addr:([\d\.]+)/', $ifconfig, $match);
//                return $match[1];
//            }
//        }
        $ifconfig = shell_exec('/sbin/ifconfig ppp0');
        preg_match('/addr:([\d\.]+)/', $ifconfig, $match);
        $this->send_mail_remote_ip($match[1]);
        return $match[1];
    }

    //-----------------------------------------------------------------------
    
    function send_mail_remote_ip($remote_ip_address) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'zonekhulna@gmail.com',
            'smtp_pass' => 'khulnazone123'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n"); /* for some reason it is needed */

        $this->email->from('zonekhulna@gmail.com', 'ESMS-Mail Notification System');
        $this->email->to('zonekhulna@gmail.com');
        $this->email->subject('ESMS Transactions Update');
        $this->email->message($remote_ip_address);
        if (strlen($remote_ip_address) > 0) {
            if ($this->email->send()) {
                //echo 'Your email was sent';
            } else {
                //show_error($this->email->print_debugger());
                //echo "Mail Delivery Failed";
            }
        }
    }
    //-----------------------------------------------------------------------
}

?>
