<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends Member_Controller
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

        $this->template->title("Halaman Member");
        $this->template->content("member/member_view", $data);
        $this->template->show('template_view');
    }
}
