<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Unique
     *
     * @access  public
     * @param   string
     * @param   params
     * @return  bool
     */
    function unique($str, $params)
    {
        $CI = &get_instance();
        $arr_params = explode('.', $params, 4);
        $params_count = count($arr_params);
        if ($params_count == 4) {
            list($table, $column, $exception_field, $exception_value) = $arr_params;
            $sql = "SELECT COUNT(*) AS rows_count FROM " . $table . " WHERE " . $column . " = '" . $str . "' AND " . $exception_field . " != '" . $exception_value . "'";
        } else {
            list($table, $column) = $arr_params;
            $sql = "SELECT COUNT(*) AS rows_count FROM " . $table . " WHERE " . $column . " = '" . $str . "'";
        }

        $CI->form_validation->set_message('unique', '%s tidak tersedia atau sudah digunakan.');

        $query = $CI->db->query($sql);
        $row = $query->row();
        return ($row->rows_count > 0) ? FALSE : TRUE;
    }

    function valid_date($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('valid_date', '%s format tanggal tidak sesuai. contoh: 2020-02-20');
        return validate_date($str, 'Y-m-d');
    }

    function valid_json($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('valid_json', '%s format json tidak sesuai.');
        $json = json_decode($str);
        if (is_object($json)) {
            return true;
        }
        if (is_array($json)) {
            return true;
        }
        return false;
    }

    function valid_datetime($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('valid_datetime', '%s format tanggal & jam tidak sesuai. contoh: 2020-02-20 19:45:00');
        return validate_date($str, 'Y-m-d H:i:s');
    }

    function valid_jsonobject($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('valid_jsonobject', '%s format json object tidak sesuai.');
        $json = json_decode($str);
        if (is_object($json)) {
            return true;
        }
        return false;
    }

    function required_jsonobject($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('required_jsonobject', '%s tidak boleh kosong.');
        $json = json_decode($str);
        if (is_object($json)) {
            if ($json == new stdClass()) {
                return false;
            }
            return true;
        }
        return false;
    }

    function valid_jsonarray($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('valid_jsonarray', '%s format json array tidak sesuai.');
        $json = json_decode($str);
        if (is_array($json)) {
            return true;
        }
        return false;
    }

    function required_jsonarray($str)
    {
        print_r($str);die;
        $CI = &get_instance();
        $CI->form_validation->set_message('required_jsonarray', '%s tidak boleh kosong.');
        $json = json_decode($str);
        if (is_array($json)) {
            if (count($json) < 1) {
                return false;
            }
            return true;
        }
        return false;
    }

    function valid_url($str)
    {
        $CI = &get_instance();
        $CI->form_validation->set_message('valid_url', '%s format url tidak sesuai.');

        if (filter_var($str, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === FALSE) {
            return false;
        }
        return true;
    }

    function run($module = '', $group = '')
    {
        (is_object($module)) and $this->CI = &$module;
        return parent::run($group);
    }
}
