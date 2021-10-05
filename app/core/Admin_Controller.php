<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Controller extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            redirect('admin/login');
        }
    }
}
