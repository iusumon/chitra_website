<?php

class Acc_balance extends Controller {


    var $data;

    //------------------------------------------------------------------------------
    function __construct() {
        parent::__construct();
    }

    //------------------------------------------------------------------------------
    function check_login_status() {
        if ($this->session->userdata('logged_in_cum') != TRUE) {
            exit(0);
        }
    }

    //----------------------------------------------------------
    function index() {
        // $this->session->unset_userdata('logged_in_cum');
        $this->display_data();
        $this->load->view('vacc_balance', $this->data);
    }
    //----------------------------------------------------------------------
    function display_data() {
//        $this->check_login_status();
        $this->load->library('table');
        $tmpl = array('table_open' => '<table id="record_account">', 'heading_row_start' => '<thead><tr>', 'heading_row_end' => '</tr></thead><tbody>');
        $this->table->set_template($tmpl);

        $this->table->set_heading('Name', 'Address', 'Account No', 'Type', 'Balance', 'Ledger');
        $this->data['table_account'] = $this->table->generate();
    }

    //----------------------------------------------------------
    function load_login() {
        $mobile = $this->input->post('mobile', true);
        // $this->load->view('vlogin');
    }

    //------------------------------------------------------------------------------
    function get_account_details() {
        $sql= "CALL sp_account_details('" . $this->session->userdata('mobile_cum'). "')";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            echo json_encode($query->result());
        } else {
            echo json_encode(array());
        }
    }
    //------------------------------------------------------------------------------

    function checkAccount() {
        $mobile = $this->input->post('mobile', true);
        echo true;
    }

    //------------------------------------------------------------------------------
    function load_account() {
        // $this->check_login_status();
        //check session user id and password
        $this->load->view('vaccount');

    }

    //------------------------------------------------------------------------------
    function exit_all() {
        $this->check_login_status();
        //		$this->session->sess_destroy();
        $this->load->view('vlogin');
        // $this->Model_db_integrity_check->send_mail();
    }

    //------------------------------------------------------------------------------
}
?>
