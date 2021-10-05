<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Base_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        defined('TEMPLATE_ASSET_URL') or define('TEMPLATE_ASSET_URL', site_url('assets/'));

        if (empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }
    }

    public function res_validation($err_msg, $err_data)
    {
        echo json_encode(array(
            'status' => 'validation',
            'msg' => 'Cek kembali form Anda!',
            'error_msg' => $err_msg,
            'error_data' => (object) $err_data,
        ));
        
        exit();
    }

    public function res_error($msg)
    {
        echo json_encode(array(
            'status' => 'failed',
            'msg' => $msg,
        ));
        exit();
    }

    public function res_success($msg, $data)
    {
        echo json_encode(array(
            'status' => 'success',
            'msg' => $msg,
            'data' => $data
        ));
        exit();
    }
}
