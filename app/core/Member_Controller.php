<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Member_Controller extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['member_logged_in']) || !$_SESSION['member_logged_in']) {
            redirect('login');
        }
    }
}
