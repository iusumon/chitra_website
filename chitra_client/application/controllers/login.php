<?php

class Login extends Controller {

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
//        $this->display_data();
        $this->load->view('vlogin', $this->data);
    }
    //------------------------------------------------------------------------------
    function get_account_details($mobile_no) {
        $sql= "CALL sp_account_details('" . $mobile_no. "')";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            echo json_encode($query->result());
        } else {
            echo json_encode(array());
        }
    }
    //------------------------------------------------------------------------------
    function checkLogin() {
        $this->obj = json_decode($this->input->post('jsarray', TRUE));
		$mobile = $this->obj->mobile;
		$passwd = $this->obj->passwd; 
        $sql = "SELECT id, name, address_1, mobile_no, passwd FROM tbl_client WHERE mobile_no ='" . $mobile . "' UNION SELECT id, name, address_1, mobile_no, passwd FROM tbl_member WHERE mobile_no ='" . $mobile . "'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                if ($row->mobile_no == $mobile AND password_verify($passwd, $row->passwd)) {
                    $arr_login_data = array(
                        'mobile_cum' => $mobile,
                        'user_id_cum' => $row->id,
                        'logged_in_cum' => TRUE,
                        'prj_name_cum' => "Chitra Multipurpose Co-operative Society Ltd."
                    );
                    $this->session->set_userdata($arr_login_data);

//                    $access_time = date("Y-m-d H:i:s", time());
//                    $this->db->set('last_access_time', $access_time);
//                    $this->db->where('id', $this->session->userdata('user_id_cum'), TRUE);
//                    $this->db->update('tbl_user');

                    echo true;
                    return;
                } else {
                    echo false;
                    return;
                }
            }
        }

        if ($query->num_rows() < 1) {
            $this->load->view('vlogin');
        }
    }
    //------------------------------------------------------------------------------
    function load_account() {
        // $this->check_login_status();
        //check session user id and password
        $this->load->view('vacc_balance');

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
