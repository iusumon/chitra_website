<?php

class Change_passwd extends Controller {

    var $gr_id;
    var $obj;
    var $data;

    //----------------------------------------------------------------------
    function __construct() {
        parent::__construct();
        //$this->validate();
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
            $this->load->view('vchange_passwd', $this->data);
        } else {
            $this->load->view('vlogin');
        }
    }

    //----------------------------------------------------------------------
    function save_data() {
        $this->check_login_status();
        $this->obj = json_decode($this->input->post('jsarray', TRUE));
        $msg_validation['valid'] = $this->validate();
        if ($msg_validation['valid'] == "Success") {
            $this->db->set('passwd', password_hash($this->obj->confirm_passwd, PASSWORD_DEFAULT), TRUE);
            $this->db->where('id', $this->session->userdata('user_id_cum'), TRUE);
            if(substr($this->session->userdata('user_id_cum'), 0, 1) == 'C') {
                $this->db->update('tbl_client');
            } else {
                $this->db->update('tbl_member');
            }
            $msg_validation['cur_user_id'] = $this->session->userdata('user_id_cum');
            echo json_encode($msg_validation);
        } else {
            echo json_encode($msg_validation);
        }
    }

    //----------------------------------------------------------------------
    function validate() {
        $this->check_login_status();
        $sql = "SELECT id, name, address_1, mobile_no, passwd FROM tbl_client WHERE id ='" . $this->session->userdata('user_id_cum') . "' UNION SELECT id, name, address_1, mobile_no, passwd FROM tbl_member WHERE id ='" . $this->session->userdata('user_id_cum') . "'";
        $this->db->where('id', $this->session->userdata('user_id_cum'));
        $query = $this->db->query($sql);
        $row = $query->row();
        if(strlen($this->obj->cur_passwd) < 1 or strlen($this->obj->new_passwd) < 1 or strlen($this->obj->confirm_passwd) < 1) {
            return "Blank Password";
        }
        if (!password_verify($this->obj->cur_passwd, $row->passwd)) {
            return "Please Enter Correct Previous Password";
        } 
        if ($this->obj->new_passwd != $this->obj->confirm_passwd) {
            return "Please Enter Correct New Password";
        }
        return "Success";
    }

    //----------------------------------------------------------------------
}
?>