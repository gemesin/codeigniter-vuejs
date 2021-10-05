<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $data['page_name'] = "Beranda";

        $this->template->title("Dashboard | Administrator");
        $this->template->content("admin/dashboard_view", $data);
        $this->template->show('template_admin_view');
    }
}
