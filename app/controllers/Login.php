<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Public_Controller
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

        $this->template->title("Login | Member");
        $this->template->content("member/login_view", $data);
        $this->template->show('template_login_view');
    }

    public function verify()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|htmlspecialchars|required');

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $email = addslashes($this->input->post('email'));
        $password = addslashes($this->input->post('password'));

        $sql = "
            SELECT *
            FROM member 
            WHERE member_email = '{$email}'
            LIMIT 1
        ";

        $query_member = $this->db->query($sql);

        if ($query_member->num_rows() > 0) {
            $row = $query_member->row();

            if (password_verify($password, $row->member_password)) {
                
                $this->db->where('member_id', $row->member_id);
                $this->db->update('member', array('member_last_login_datetime' => date('Y-m-d H:i:s')));

                $arr_session = array(
                    'member_id' => $row->member_id,
                    'member_name' => $row->member_name,
                    'member_email' => $row->member_email,
                    'member_mobile_phone' => $row->member_mobile_phone,
                    'member_birth_date' => $row->member_birth_date,
                    'member_gender' => $row->member_gender,
                    'member_id_number' => $row->member_id_number,
                    'member_image_url' => $row->member_image_url,
                    'member_last_login_datetime' => $row->member_last_login_datetime,
                    'member_logged_in' => TRUE,
                );
                $this->session->set_userdata($arr_session);

                $redirect = isset($_SESSION['redirect']) ? trim($_SESSION['redirect']) : '';

                $this->res_success("Login berhasil!", array(
                    "results" => (object) $arr_session,
                    "redirect" => empty($redirect) ? "member" : $redirect
                ));
            }
        }

        $this->res_error("Username atau Password salah!");
    }
}
