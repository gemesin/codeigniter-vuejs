<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            redirect('admin/dashboard');
        }
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $data = [];

        $this->template->title("Login | Administrator");
        $this->template->content("admin/login_view", $data);
        $this->template->show('template_admin_login_view');
    }

    public function verify()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|htmlspecialchars|required');

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $username = addslashes($this->input->post('username'));
        $password = addslashes($this->input->post('password'));

        $sql = "
            SELECT *
            FROM admin 
            WHERE admin_username = '{$username}'
            LIMIT 1
        ";

        $query_admin = $this->db->query($sql);

        if ($query_admin->num_rows() > 0) {
            $row = $query_admin->row();

            if (password_verify($password, $row->admin_password)) {
                
                $this->db->where('admin_id', $row->admin_id);
                $this->db->update('admin', array('admin_last_login_datetime' => date('Y-m-d H:i:s')));

                $arr_session = array(
                    'admin_id' => $row->admin_id,
                    'admin_username' => $row->admin_username,
                    'admin_name' => $row->admin_name,
                    'admin_email' => $row->admin_email,
                    'admin_last_login_datetime' => $row->admin_last_login_datetime,
                    'admin_logged_in' => TRUE,
                );
                $this->session->set_userdata($arr_session);

                $redirect = isset($_SESSION['redirect']) ? trim($_SESSION['redirect']) : '';

                $this->res_success("Login berhasil!", array(
                    "results" => (object) $arr_session,
                    "redirect" => empty($redirect) ? "dashboard" : $redirect
                ));
            }
        }

        $this->res_error("Username atau Password salah!");
    }
}
