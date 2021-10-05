<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $arr_session = array(
            'admin_id',
            'admin_username',
            'admin_name',
            'admin_email',
            'admin_image_url',
            'admin_last_login',
            'admin_logged_in',
        );

        $this->session->unset_userdata($arr_session);

        if ($this->session->userdata('admin_logged_in')) {
            $this->session->sess_destroy();
        }

        redirect('admin/login');
    }
}
