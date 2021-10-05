<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends Member_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $arr_session = array(
            'member_id',
            'member_name',
            'member_email',
            'member_mobile_phone',
            'member_birth_date',
            'member_gender',
            'member_id_number',
            'member_image_url',
            'member_last_login_datetime',
            'member_logged_in',
        );

        $this->session->unset_userdata($arr_session);

        if ($this->session->userdata('member_logged_in')) {
            $this->session->sess_destroy();
        }

        redirect('login');
    }
}
