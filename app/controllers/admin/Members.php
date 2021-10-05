<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Members extends Admin_Controller
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

        $data['page_name'] = "Member";

        $this->template->title("Member | Administrator");
        $this->template->content("admin/members_view", $data);
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

        $table = "member";
        $join = "";
        $where = "";

        if ($dir != 'ASC' && $dir != 'DESC') {
            $dir = 'DESC';
        }

        $start = ($page - 1) * $limit;

        if ($search != '') {
            $where .= ' AND (
                    member_name LIKE "%' . $search . '%" OR 
                    member_email LIKE "%' . $search . '%" OR 
                    member_mobile_phone LIKE "%' . $search . '%" OR 
                    member_gender LIKE "%' . $search . '%" OR 
                    member_id_number LIKE "%' . $search . '%"
                )
            ';
        }

        $query_search = '';

        $arr_field = array(
            'member_id',
            'member_name',
            'member_email',
            'member_mobile_phone',
            'member_gender',
            'member_birth_date',
            'member_id_number',
            'member_image_url',
            'member_last_login_datetime',
        );

        if (is_array($filter)) {
            $query_search = search_input($filter, $arr_field);
        }

        if (!in_array($sort, $arr_field)) {
            $sort = 'member_id';
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

        $this->res_success("Tambah member berhasil", array());
    }

    public function edit()
    {
        $this->form_validation->set_rules('member_id', 'ID', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_name', 'Nama', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_email', 'Email', 'trim|htmlspecialchars|valid_email|required|unique[member.member_email.member_id.' . $this->input->post('member_id') . ']');
        $this->form_validation->set_rules('member_mobile_phone', 'No. Handphone', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_birth_date', 'Tgl. Lahir', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_gender', 'Jenis Kelamin', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('member_id_number', 'No. KTP', 'trim|htmlspecialchars|required');

        if(!empty($this->input->post('member_password'))){
            $this->form_validation->set_rules('member_password', 'Password', 'trim|htmlspecialchars|required');
        }

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $id = addslashes($this->input->post('member_id'));
        $name = addslashes($this->input->post('member_name'));
        $email = addslashes($this->input->post('member_email'));
        $mobile_phone = addslashes($this->input->post('member_mobile_phone'));
        $birth_date = addslashes($this->input->post('member_birth_date'));
        $gender = addslashes($this->input->post('member_gender'));
        $id_number = addslashes($this->input->post('member_id_number'));
        $password = $this->input->post('member_password');
        $image_url = $this->input->post('member_image_url');

        $update = array();
        $update['member_name'] = $name;
        $update['member_email'] = $email;
        $update['member_mobile_phone'] = $mobile_phone;
        $update['member_birth_date'] = $birth_date;
        $update['member_gender'] = $gender;
        $update['member_id_number'] =  $id_number;
        $update['member_image_url'] = $image_url;

        if(!empty($password)){
            $update['member_password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->db->where('member_id', $id);
        $this->db->update('member', $update);

        $this->res_success("Ubah member berhasil", array());
    }

    public function remove()
    {
        $this->form_validation->set_rules('member_id', 'ID', 'trim|htmlspecialchars|required');

        if (!$this->form_validation->run()) {
            $this->res_validation(validation_errors(), $this->form_validation->error_array());
        }

        $id = addslashes($this->input->post('member_id'));

        $this->db->where('member_id', $id);
        $this->db->delete('member');

        $this->res_success("Hapus member berhasil", array());
    }
}
