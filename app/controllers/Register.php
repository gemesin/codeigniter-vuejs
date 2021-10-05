<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in']) {
            redirect('member');
        }
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Register | Member");
        $this->template->content("member/register_view", $data);
        $this->template->show('template_login_view');
    }

    public function save()
    {
        $this->form_validation->set_rules('member_name', 'Nama', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_email', 'Email', 'trim|htmlspecialchars|valid_email|required|unique[member.member_email]');
        $this->form_validation->set_rules('member_mobile_phone', 'No. Handphone', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_birth_date', 'Tgl. Lahir', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_gender', 'Jenis Kelamin', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_id_number', 'No. KTP', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_password', 'Password', 'trim|htmlspecialchars|required');

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $name = addslashes($this->input->post('member_name'));
        $email = addslashes($this->input->post('member_email'));
        $mobile_phone = addslashes($this->input->post('member_mobile_phone'));
        $birth_date = addslashes($this->input->post('member_birth_date'));
        $gender = addslashes($this->input->post('member_gender'));
        $id_number = addslashes($this->input->post('member_id_number'));
        $password = $this->input->post('member_password');
        $image_url = $this->input->post('member_image_url');

        $insert = array();
        $insert['member_name'] = $name;
        $insert['member_email'] = $email;
        $insert['member_mobile_phone'] = $mobile_phone;
        $insert['member_birth_date'] = $birth_date;
        $insert['member_gender'] = $gender;
        $insert['member_id_number'] =  $id_number;
        $insert['member_password'] = password_hash($password, PASSWORD_DEFAULT);
        $insert['member_image_url'] = $image_url;

        $this->db->insert('member', $insert);

        $this->res_success("Registrasi berhasil", array());
    }
}
