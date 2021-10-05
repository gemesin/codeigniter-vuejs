<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends Admin_Controller
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

        $data['page_name'] = "User";

        $this->template->title("User | Administrator");
        $this->template->content("admin/users_view", $data);
        $this->template->show('template_admin_view');
    }

    public function get_data()
    {
        $limit = (int) $this->input->get('limit') <= 0 ? 10 : (int) $this->input->get('limit');
        $page = (int) $this->input->get('page') <= 0 ? 1 : (int) $this->input->get('page');
        $filter = (array) $this->input->get('filter');
        $sort = (string) $this->input->get('sort');
        $dir = strtoupper($this->input->get('dir'));

        $search = (string) ($this->input->get('search')) ? $this->input->get('search') : '';

        $table = "admin";
        $join = "";
        $where = "";

        if ($dir != 'ASC' && $dir != 'DESC') {
            $dir = 'DESC';
        }

        $start = ($page - 1) * $limit;

        if ($search != '') {
            $where .= ' AND (
                    admin_username LIKE "%' . $search . '%" OR 
                    admin_name LIKE "%' . $search . '%" OR 
                    admin_email LIKE "%' . $search . '%"
                )
            ';
        }

        $query_search = '';

        $arr_field = array(
            'admin_id',
            'admin_username',
            'admin_name',
            'admin_email',
            'admin_last_login_datetime'
        );

        if (is_array($filter)) {
            $query_search = search_input($filter, $arr_field);
        }

        if (!in_array($sort, $arr_field)) {
            $sort = 'admin_id';
        }

        $where_detail = "";

        $str_field_search = empty($arr_field) ? '*' : implode(',', $arr_field);

        $sql_get = "
            SELECT SQL_CALC_FOUND_ROWS
            $str_field_search
            FROM $table
            $join
            WHERE 1
            $where_detail
            $where
            $query_search
            ORDER BY $sort $dir
            LIMIT $start, $limit
        ";

        $result = $this->db->query($sql_get);

        $total = 0;

        $sql_total = "
            SELECT FOUND_ROWS() AS total
        ";

        $query_total = $this->db->query($sql_total);

        if ($query_total->num_rows() > 0) {
            $total = $query_total->row('total');
        }

        $data = array();

        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $data[] = array_map("convertNullToString", $row);
            }
        }

        $output = array(
            'results' => $data,
            'pagination' => page_generate($total, $page, $limit)
        );

        $this->res_success("Berhasil mendapatkan data", $output);
    }

    public function add()
    {
        $this->form_validation->set_rules('admin_name', 'Nama', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('admin_email', 'Email', 'trim|htmlspecialchars|valid_email|required|unique[admin.admin_email]');
        $this->form_validation->set_rules('admin_username', 'Username', 'trim|htmlspecialchars|required|unique[admin.admin_username]');
        $this->form_validation->set_rules('admin_password', 'Password', 'trim|htmlspecialchars|required');

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $name = addslashes($this->input->post('admin_name'));
        $email = addslashes($this->input->post('admin_email'));
        $username = addslashes($this->input->post('admin_username'));
        $password = $this->input->post('admin_password');

        $insert = array();
        $insert['admin_name'] = $name;
        $insert['admin_email'] = $email;
        $insert['admin_username'] =  $username;
        $insert['admin_password'] = password_hash($password, PASSWORD_DEFAULT);

        $this->db->insert('admin', $insert);

        $this->res_success("Tambah user berhasil", array());
    }

    public function edit()
    {
        $this->form_validation->set_rules('admin_id', 'ID', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('admin_name', 'Nama', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('admin_email', 'Email', 'trim|htmlspecialchars|valid_email|required|unique[admin.admin_email.admin_id.' . $this->input->post('admin_id') . ']');
        $this->form_validation->set_rules('admin_username', 'Username', 'trim|htmlspecialchars|required|unique[admin.admin_username.admin_id.' . $this->input->post('admin_id') . ']');

        if(!empty($this->input->post('admin_password'))){
            $this->form_validation->set_rules('admin_password', 'Password', 'trim|htmlspecialchars|required');
        }

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $id = addslashes($this->input->post('admin_id'));
        $name = addslashes($this->input->post('admin_name'));
        $email = addslashes($this->input->post('admin_email'));
        $username = addslashes($this->input->post('admin_username'));
        $password = $this->input->post('admin_password');

        $update = array();
        $update['admin_name'] = $name;
        $update['admin_email'] = $email;
        $update['admin_username'] =  $username;

        if(!empty($password)){
            $update['admin_password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->db->where('admin_id', $id);
        $this->db->update('admin', $update);

        $this->res_success("Ubah user berhasil", array());
    }

    public function remove()
    {
        $this->form_validation->set_rules('admin_id', 'ID', 'trim|htmlspecialchars|required');

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $id = addslashes($this->input->post('admin_id'));

        $this->db->where('admin_id', $id);
        $this->db->delete('admin');

        $this->res_success("Hapus user berhasil", array());
    }
}
