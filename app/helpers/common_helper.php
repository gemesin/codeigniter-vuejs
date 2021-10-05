<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('pr')) {

    function pr($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}

if (!function_exists('is_nominal')) {

    function is_nominal($value)
    {
        if (is_numeric($value)) {
            if (strpos($value, ".") !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('romanic_number')) {
    function romanic_number($integer, $upcase = true)
    {
        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }
        return $return;
    }
}

if (!function_exists('convertNullToString')) {
    function convertNullToString($v)
    {
        return (is_null($v)) ? "" : $v;
    }
}

if (!function_exists('getFirstParagrap')) {
    function getFirstParagrap($string)
    {
        $string = substr($string, 0, strpos($string, "</p>") + 4);
        return $string;
    }
}

if (!function_exists('startsWith')) {

    function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
}

if (!function_exists('endsWith')) {

    function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
}

if (!function_exists('bulat_rp')) {

    function bulat_rp($uang)
    {
        $akhir = 0;
        $sisa_angka = substr($uang, -2);
        if ($sisa_angka != '00') {
            if ($sisa_angka < 100) {
                $akhir = $uang + (100 - $sisa_angka);
                return $akhir;
            } else {
                return $uang;
            }
        } else {
            return $uang;
        }
    }
}

if (!function_exists('bulat_rp_bawah')) {
    if (!function_exists('bulat_rp_bawah')) {

        function bulat_rp_bawah($uang)
        {
            $akhir = 0;
            $sisa_angka = substr($uang, -2);
            if ($sisa_angka != '00') {
                $akhir = $uang - $sisa_angka;
                return $akhir;
            } else {
                return $uang;
            }
        }
    }
}

if (!function_exists('convert_month')) {

    function convert_month($month, $lang = 'en')
    {
        $month = (int) $month;
        switch ($lang) {
            case 'id':
                $arr_month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                break;

            default:
                $arr_month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                break;
        }
        $month_converted = $arr_month[$month - 1];

        return $month_converted;
    }
}

if (!function_exists('convert_date')) {

    function convert_date($date, $type = 'string', $format = '.', $lang = 'id')
    {
        if (!empty($date)) {
            $date = substr($date, 0, 10);
            if ($type == 'num') {
                $date_converted = str_replace('-', $format, $date);
            } else {
                $year = substr($date, 0, 4);
                $month = substr($date, 5, 2);
                $month = convert_month($month, $lang);
                $day = substr($date, 8, 2);

                $date_converted = $day . ' ' . $month . ' ' . $year;
            }
        } else {
            $date_converted = '-';
        }
        return $date_converted;
    }
}

if (!function_exists('convert_datetime')) {

    function convert_datetime($date, $type = 'string', $formatdate = '.', $formattime = ':', $lang = 'id')
    {

        if (!empty($date)) {
            if ($type == 'num') {
                $date_converted = str_replace('-', $formatdate, str_replace(':', $formattime, $date));
            } else {
                $year = substr($date, 0, 4);
                $month = substr($date, 5, 2);
                $month = convert_month($month, $lang);
                $day = substr($date, 8, 2);
                $time = strlen($date) > 10 ? substr($date, 11, 8) : '';
                $time = str_replace(':', $formattime, $time);

                $date_converted = $day . ' ' . $month . ' ' . $year . ' ' . $time;
            }
        } else {
            $date_converted = '-';
        }
        return $date_converted;
    }
}

if (!function_exists('terbilang')) {

    function terbilang($x)
    {
        $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($x < 12)
            return " " . $abil[$x];
        elseif ($x < 20)
            return terbilang($x - 10) . "belas";
        elseif ($x < 100)
            return terbilang($x / 10) . " puluh" . terbilang($x % 10);
        elseif ($x < 200)
            return " seratus" . Terbilang($x - 100);
        elseif ($x < 1000)
            return terbilang($x / 100) . " ratus" . terbilang($x % 100);
        elseif ($x < 2000)
            return " seribu" . terbilang($x - 1000);
        elseif ($x < 1000000)
            return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
        elseif ($x < 1000000000)
            return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
    }
}

if (!function_exists('validate_date')) {

    function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

if (!function_exists('search_input')) {

    function search_input($where_filter = array(), $field_allowed = array())
    {
        $sql_search = '';

        if ($where_filter != null) {
            foreach ($where_filter as $row) {
                $type = isset($row['type']) ? $row['type'] : '';
                $field = isset($row['field']) ? $row['field'] : '';
                $value = isset($row['value']) ? $row['value'] : '';
                $comparison = isset($row['comparison']) ? $row['comparison'] : '';

                if (!in_array($field, $field_allowed)) {
                    $field = '';
                }

                if ($field == '' || $value == '') {
                    $type = '';
                }

                switch ($type) {
                    case 'string':
                        $arr_allowed = array('=', '<', '>', '==');
                        if (!in_array($comparison, $arr_allowed)) {
                            $comparison = '=';
                        }
                        switch ($comparison) {
                            case '==':
                                $sql_search .= " AND " . $field . " = '" . $value . "'";
                                break;
                            case '<':
                                $sql_search .= " AND " . $field . " LIKE '" . $value . "%'";
                                break;
                            case '>':
                                $sql_search .= " AND " . $field . " LIKE '%" . $value . "'";
                                break;
                            case '=':
                                $sql_search .= " AND " . $field . " LIKE '%" . $value . "%'";
                                break;
                        }
                        break;
                    case 'numeric':
                        if (is_numeric($value)) {
                            $arr_allowed = array('=', '<', '>', '<=', '>=', '<>');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = '=';
                            }
                            $sql_search .= " AND " . $field . " " . $comparison . " " . $value;
                        }
                        break;
                    case 'list':
                        if (strstr($value, '::')) {
                            $arr_allowed = array('yes', 'no', 'bet');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = 'yes';
                            }
                            $fi = explode('::', $value);
                            for ($q = 0; $q < count($fi); $q++) {
                                $fi[$q] = "'" . $fi[$q] . "'";
                            }
                            $value = implode(',', $fi);
                            if ($comparison == 'yes') {
                                $sql_search .= " AND " . $field . " IN (" . $value . ")";
                            }
                            if ($comparison == 'no') {
                                $sql_search .= " AND " . $field . " NOT IN (" . $value . ")";
                            }
                            if ($comparison == 'bet') {
                                $sql_search .= " AND " . $field . " BETWEEN " . $fi[0] . " AND " . $fi[1];
                            }
                        } else {
                            $sql_search .= " AND " . $field . " = '" . $value . "'";
                        }
                        break;
                    case 'date':
                        if (endsWith($field, 'date') || endsWith($field, 'datetime')) {
                            $value1 = '';
                            $value2 = '';
                            if (strstr($value, '::')) {
                                $date_value = explode('::', $value);
                                $value1 = $date_value[0];
                                $value2 = $date_value[1];
                            } else {
                                $value1 = $value;
                            }

                            if (endsWith($field, 'datetime')) {
                                $field = 'date(' . $field . ')';
                            }

                            $arr_allowed = array('=', '<', '>', '<=', '>=', '<>', 'bet');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = '=';
                            }
                            if ($comparison == 'bet') {
                                if (validate_date($value1) && validate_date($value2)) {
                                    $sql_search .= " AND " . $field . " BETWEEN '" . $value1 . "' AND '" . $value2 . "'";
                                }
                            } else {
                                if (validate_date($value1)) {
                                    $sql_search .= " AND " . $field . " " . $comparison . " '" . $value1 . "'";
                                }
                            }
                        }

                        if ($field == "created_at") {
                            $value1 = '';
                            $value2 = '';
                            if (strstr($value, '::')) {
                                $date_value = explode('::', $value);
                                $value1 = $date_value[0];
                                $value2 = $date_value[1];
                            } else {
                                $value1 = $value;
                            }

                            $field = 'date(' . $field . ')';

                            $arr_allowed = array('=', '<', '>', '<=', '>=', '<>', 'bet');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = '=';
                            }
                            if ($comparison == 'bet') {
                                if (validate_date($value1) && validate_date($value2)) {
                                    $sql_search .= " AND " . $field . " BETWEEN '" . $value1 . "' AND '" . $value2 . "'";
                                }
                            } else {
                                if (validate_date($value1)) {
                                    $sql_search .= " AND " . $field . " " . $comparison . " '" . $value1 . "'";
                                }
                            }
                        }
                        break;
                }
            }
        }

        return $sql_search;
    }
}

if (!function_exists('pageGenerate')) {

    function page_generate($total, $pagenum, $limit)
    {
        $total_page = ceil($total / $limit);

        //------------- Prev page
        $prev = $pagenum - 1;
        if ($prev < 1) {
            $prev = 0;
        }
        //------------------------
        //------------- Next page
        $next = $pagenum + 1;
        if ($next > $total_page) {
            $next = 0;
        }
        //----------------------

        $from = 1;
        $to = $total_page;

        $to_page = $pagenum - 2;
        if ($to_page > 0) {
            $from = $to_page;
        }

        if ($total_page >= 5) {
            if ($total_page > 0) {
                $to = 5 + $to_page;
                if ($to > $total_page) {
                    $to = $total_page;
                }
            } else {
                $to = 5;
            }
        }

        #looping kotak pagination
        $firstpage_istrue = false;
        $lastpage_istrue = false;
        $detail = [];

        if ($total_page <= 1) {
            $detail = [];
        } else {
            for ($i = $from; $i <= $to; $i++) {
                $detail[] = $i;
            }
            if ($from != 1) {
                $firstpage_istrue = true;
            }
            if ($to != $total_page) {
                $lastpage_istrue = true;
            }
        }

        $total_display = $limit;
        if ($next == 0) {
            $total_display = $total % $limit;
        }

        $pagination = array(
            'total_data' => $total,
            'total_page' => $total_page,
            'total_display' => $total_display,
            'first_page' => $firstpage_istrue,
            'last_page' => $lastpage_istrue,
            'prev' => $prev,
            'current' => $pagenum,
            'next' => $next,
            'detail' => $detail
            // 'detail' => json_encode($detail)
        );

        return $pagination;
    }
}

if (!function_exists('sanitizationResponse')) {
    function sanitization_response($data)
    {
        foreach ($data as $key => $val) {
            if (is_null($val)) {
                $data[$key] = '';
            }
            if (endsWith($key, 'jsonobject')) {
                $data[$key] = json_decode(empty($val) ? '{}' : $val);
            }
            if (endsWith($key, 'jsonarray')) {
                $data[$key] = json_decode(empty($val) ? '[]' : $val);
            }
            if (endsWith($key, 'date')) {
                $value = '';
                if (!empty($val) && $val != '0000-00-00') {
                    $value = strftime('%d %B %Y', strtotime($val));
                }
                $data[$key . '_format'] = $value;
            }
            if (endsWith($key, 'datetime')) {
                $value = '';
                if (!empty($val) && $val != '0000-00-00 00:00:00') {
                    $value = strftime('%A, %d %B %Y %H:%M', strtotime($val));
                }
                $data[$key . '_format'] = $value;
            }
            if (is_numeric($val) && !endsWith($key, 'id') && !strstr($key, 'is') && !endsWith($key, 'phone')) {
                $data[$key . '_format'] = number_format($val, 0, ',', '.');
            }
        }
        return $data;
    }
}

if (!function_exists('generateDetailQuery')) {
    function generate_detail_query($from_table_and_join, $where_detail, $field_show = array())
    {
        $CI = &get_instance();
        $CI->load->database();

        $query_filter = 'WHERE 1 ';

        $result_arr = array();

        $where_detail = trim($where_detail);
        if (!empty($where_detail)) {
            $query_filter .= " AND $where_detail ";
        }

        $str_field_search = empty($field_show) ? '*' : implode(',', $field_show);

        $sql_get = " SELECT
            $str_field_search
            $from_table_and_join
            $query_filter
        ";

        $result = $CI->db->query($sql_get);

        $result_arr['data'] = array();

        if ($result->num_rows() > 0) {
            $data_detail = $result->row_array();
            foreach ($data_detail as $key => $val) {
                if (is_null($val)) {
                    $data_detail[$key] = '';
                }
                if (endsWith($key, 'jsonobject')) {
                    $data_detail[$key] = json_decode(empty($val) ? '{}' : $val);
                }
                if (endsWith($key, 'jsonarray')) {
                    $data_detail[$key] = json_decode(empty($val) ? '[]' : $val);
                }
                if (endsWith($key, 'date')) {
                    $value = '';
                    if (!empty($val) && $val != '0000-00-00') {
                        $value = strftime('%d %B %Y', strtotime($val));
                    }
                    $data_detail[$key . '_format'] = $value;
                }
                if (endsWith($key, 'datetime')) {
                    $value = '';
                    if (!empty($val) && $val != '0000-00-00 00:00:00') {
                        $value = strftime('%A, %d %B %Y %H:%M', strtotime($val));
                    }
                    $data_detail[$key . '_format'] = $value;
                }
                if (is_numeric($val) && !endsWith($key, 'id') && !strstr($key, 'is') && !endsWith($key, 'phone')) {
                    $data_detail[$key . '_format'] = number_format($val, 0, ',', '.');
                }
            }
            $result_arr['data'] = $data_detail;
        }

        return $result_arr;
    }
}

if (!function_exists('generateOptionQuery')) {
    function generate_option_query($params, $from_table_and_join, $where_detail, $field_show = array(), $field_search = array())
    {
        $CI = &get_instance();
        $CI->load->database();

        $sort = isset($params['sort']) ? $params['sort'] : '';

        $dir = 'ASC';

        if (startsWith($sort, '-')) {
            $dir = 'DESC';
            $sort = str_replace('-', '', $sort);
        }

        if (!in_array($sort, $field_show)) {
            $sort = $field_show[0];
        }

        if ($sort != '') {
            $sort = "ORDER BY $sort $dir";
        }

        $query_filter = 'WHERE 1 ';

        $query_filter .= filter_query($params, $field_show);
        if (isset($params['filter'])) {
            $query_filter .= filter_query_array($params['filter'], $field_show);
        }
        $search = isset($params['search']) ? $params['search'] : '';
        $query_filter .= search_query($search, $field_search);

        $where_detail = trim($where_detail);
        if (!empty($where_detail)) {
            $query_filter .= " AND $where_detail ";
        }

        $str_field_search = empty($field_show) ? '*' : implode(',', $field_show);

        $sql_get = " SELECT
            $str_field_search
            $from_table_and_join
            $query_filter
            $sort
        ";

        $result = $CI->db->query($sql_get);

        $result_arr['data'] = array();

        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $result_arr['data'][] = sanitization_response($row);
            }
        }

        return $result_arr;
    }
}



if (!function_exists('generateDataQuery')) {
    function generate_data_query($params, $from_table_and_join, $where_detail, $field_show = array(), $field_search = array())
    {
        $CI = &get_instance();
        $CI->load->database();

        $limit = 10;
        if (isset($params['limit'])) {
            $limit = (int) $params['limit'] <= 0 ? 10 : (int)$params['limit'];
        }

        $page = 1;
        if (isset($params['page'])) {
            $page = (int) $params['page'] <= 0 ? 1 : (int)$params['page'];
        }

        $sort = isset($params['sort']) ? $params['sort'] : '';
        $search = isset($params['search']) ? $params['search'] : '';

        $start = ($page - 1) * $limit;

        $query_filter = 'WHERE 1 ';

        $result_arr = array();

        $query_filter .= filter_query($params, $field_show);
        if (isset($params['filter'])) {
            $query_filter .= filter_query_array($params['filter'], $field_show);
        }
        $query_filter .= search_query($search, $field_search);

        $where_detail = trim($where_detail);
        if (!empty($where_detail)) {
            $query_filter .= " AND $where_detail ";
        }

        $dir = 'ASC';

        if (startsWith($sort, '-')) {
            $dir = 'DESC';
            $sort = str_replace('-', '', $sort);
        }

        if (!in_array($sort, $field_show)) {
            $sort = $field_show[0];
        }

        if ($sort != '') {
            $sort = "ORDER BY $sort $dir";
        }

        $str_field_search = empty($field_show) ? '*' : implode(',', $field_show);

        $sql_get = " SELECT
            $str_field_search
            $from_table_and_join
            $query_filter
            $sort
            LIMIT $start, $limit
        ";

        $sql_count = " SELECT 1 as total 
            $from_table_and_join
            $query_filter
        ";

        $result = $CI->db->query($sql_get);

        $total = $CI->db->query($sql_count)->num_rows();

        $result_arr['data'] = array();

        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $result_arr['data'][] = sanitization_response($row);
            }
        }

        $result_arr['pagination'] = page_generate($total, $page, $limit);

        return $result_arr;
    }
}

if (!function_exists('filterQueryArray')) {
    function filter_query_array($where_filter = array(), $field_allowed = array())
    {
        $sql_search = '';

        if ($where_filter != null) {
            foreach ($where_filter as $row) {
                $type = isset($row['type']) ? $row['type'] : '';
                $field = isset($row['field']) ? $row['field'] : '';
                $value = isset($row['value']) ? $row['value'] : '';
                $comparison = isset($row['comparison']) ? $row['comparison'] : '';

                if (!in_array($field, $field_allowed)) {
                    $field = '';
                }

                if ($field == '' || $value == '') {
                    $type = '';
                }

                switch ($type) {
                    case 'string':
                        $arr_allowed = array('=', '<', '>', '<>', '!=');
                        if (!in_array($comparison, $arr_allowed)) {
                            $comparison = '=';
                        }
                        switch ($comparison) {
                            case '=':
                                $sql_search .= " AND " . $field . " = '" . $value . "'";
                                break;
                            case '!=':
                                $sql_search .= " AND " . $field . " != '" . $value . "'";
                                break;
                            case '<':
                                $sql_search .= " AND " . $field . " LIKE '" . $value . "%'";
                                break;
                            case '>':
                                $sql_search .= " AND " . $field . " LIKE '%" . $value . "'";
                                break;
                            case '<>':
                                $sql_search .= " AND " . $field . " LIKE '%" . $value . "%'";
                                break;
                        }
                        break;
                    case 'numeric':
                        if (is_numeric($value)) {
                            $arr_allowed = array('=', '<', '>', '<=', '>=', '<>');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = '=';
                            }
                            $sql_search .= " AND " . $field . " " . $comparison . " " . $value;
                        }
                        break;
                    case 'list':
                        if (strstr($value, '::')) {
                            $arr_allowed = array('yes', 'no', 'bet');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = 'yes';
                            }
                            $fi = explode('::', $value);
                            for ($q = 0; $q < count($fi); $q++) {
                                $fi[$q] = "'" . $fi[$q] . "'";
                            }
                            $value = implode(',', $fi);
                            if ($comparison == 'yes') {
                                $sql_search .= " AND " . $field . " IN (" . $value . ")";
                            }
                            if ($comparison == 'no') {
                                $sql_search .= " AND " . $field . " NOT IN (" . $value . ")";
                            }
                            if ($comparison == 'bet') {
                                $sql_search .= " AND " . $field . " BETWEEN " . $fi[0] . " AND " . $fi[1];
                            }
                        } else {
                            $sql_search .= " AND " . $field . " = '" . $value . "'";
                        }
                        break;
                    case 'date':
                        if (endsWith($field, 'date')) {
                            $value1 = '';
                            $value2 = '';
                            if (strstr($value, '::')) {
                                $date_value = explode('::', $value);
                                $value1 = $date_value[0];
                                $value2 = $date_value[1];
                            } else {
                                $value1 = $value;
                            }

                            $arr_allowed = array('=', '<', '>', '<=', '>=', '<>', 'bet');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = '=';
                            }
                            if ($comparison == 'bet') {
                                if (validate_date($value1) && validate_date($value2)) {
                                    $sql_search .= " AND " . $field . " BETWEEN '" . $value1 . "' AND '" . $value2 . "'";
                                }
                            } else {
                                if (validate_date($value1)) {
                                    $sql_search .= " AND " . $field . " " . $comparison . " '" . $value1 . "'";
                                }
                            }
                        }
                        if (endsWith($field, 'datetime')) {
                            $value1 = '';
                            $value2 = '';
                            if (strstr($value, '::')) {
                                $date_value = explode('::', $value);
                                $value1 = $date_value[0];
                                $value2 = $date_value[1];
                            } else {
                                $value1 = $value;
                            }

                            $arr_allowed = array('=', '<', '>', '<=', '>=', '<>', 'bet');
                            if (!in_array($comparison, $arr_allowed)) {
                                $comparison = '=';
                            }
                            if ($comparison == 'bet') {
                                if (validate_date($value1, 'Y-m-d H:i:s') && validate_date($value2, 'Y-m-d H:i:s')) {
                                    $sql_search .= " AND " . $field . " BETWEEN '" . $value1 . "' AND '" . $value2 . "'";
                                } else if (validate_date($value1) && validate_date($value2)) {
                                    $sql_search .= " AND DATE(" . $field . ") BETWEEN '" . $value1 . "' AND '" . $value2 . "'";
                                }
                            } else {
                                if (validate_date($value1, 'Y-m-d H:i:s')) {
                                    $sql_search .= " AND " . $field . " " . $comparison . " '" . $value1 . "'";
                                } else if (validate_date($value1)) {
                                    $sql_search .= " AND DATE(" . $field . ") " . $comparison . " '" . $value1 . "'";
                                }
                            }
                        }
                        break;
                }
            }
        }

        return $sql_search;
    }
}

if (!function_exists('filterQuery')) {
    function filter_query($params, $field_allowed)
    {
        unset($params['sort']);
        unset($params['page']);
        unset($params['limit']);
        unset($params['search']);
        unset($params['filter']);

        $query_filter = '';

        foreach ($params as $field => $value) {
            if (in_array($field, $field_allowed)) {
                if (is_array($value)) {
                    foreach ($value as $comparison => $val) {
                        if (endsWith($field, 'datetime')) {
                            if (!validate_date($val, 'Y-m-d H:i:s')) {
                                $val = '';
                            }
                            if ($comparison == 'le' || $comparison == 'ls' || $comparison == 'lse') {
                                $comparison = '';
                            }
                        }
                        if (endsWith($field, 'date')) {
                            if (!validate_date($val)) {
                                $val = '';
                            }
                            if ($comparison == 'le' || $comparison == 'ls' || $comparison == 'lse') {
                                $comparison = '';
                            }
                        }
                        if ($val != '') {
                            switch ($comparison) {
                                case 'eq':
                                    $query_filter .= " AND $field = '$val' ";
                                    break;

                                case 'neq':
                                    $query_filter .= " AND $field != '$val' ";
                                    break;

                                case 'lt':
                                    $query_filter .= " AND $field < '$val' ";
                                    break;

                                case 'gt':
                                    $query_filter .= " AND $field > '$val' ";
                                    break;

                                case 'lte':
                                    $query_filter .= " AND $field <= '$val' ";
                                    break;

                                case 'gte':
                                    $query_filter .= " AND $field >= '$val' ";
                                    break;

                                case 'le':
                                    $query_filter .= " AND $field LIKE '$val%' ";
                                    break;

                                case 'ls':
                                    $query_filter .= " AND $field LIKE '%$val' ";
                                    break;

                                case 'lse':
                                    $query_filter .= " AND $field LIKE '%$val%' ";
                                    break;

                                case 'in':
                                    $fi = explode(',', $val);
                                    for ($q = 0; $q < count($fi); $q++) {
                                        $fi[$q] = "'" . $fi[$q] . "'";
                                    }
                                    $val = implode(',', $fi);
                                    $query_filter .= " AND $field IN ($val) ";
                                    break;

                                case 'nin':
                                    $fi = explode(',', $val);
                                    for ($q = 0; $q < count($fi); $q++) {
                                        $fi[$q] = "'" . $fi[$q] . "'";
                                    }
                                    $val = implode(',', $fi);
                                    $query_filter .= " AND $field NOT IN ($val) ";
                                    break;

                                default:
                                    $query_filter .= " AND $field = '$val' ";
                                    break;
                            }
                        }
                    }
                } else {
                    if (endsWith($field, 'datetime')) {
                        $field = 'date(' . $field . ')';
                        if (!validate_date($value)) {
                            $value = '';
                        }
                    }
                    if (endsWith($field, 'date')) {
                        if (!validate_date($value)) {
                            $value = '';
                        }
                    }
                    if ($value != '') {
                        $query_filter .= " AND $field = '$value' ";
                    }
                }
            }
        }
        return $query_filter;
    }
}

if (!function_exists(('waNumber'))) {
    function wa_number($no, $code = '62')
    {
        $no = str_replace(' ', '', $no);
        $no = str_replace('-', '', $no);
        if (startsWith($no, '+')) {
            $no = ltrim($no, "+");
        }

        $tempNo = '';

        if (startsWith($no, $code)) {
            $tempNo = $no;
        } else {
            if (startsWith($no, '0')) {
                $tempNo = $code . ltrim($no, "0");
            } else {
                $tempNo = $code . $no;
            }
        }

        return $tempNo != '' ? $tempNo : $no;
    }
}

if (!function_exists('searchQuery')) {
    function search_query($search, $field)
    {
        $query = '';
        if (is_array($field)) {
            foreach ($field as $row) {
                if (!empty($search)) {
                    if (!endsWith($row, 'datetime') && !endsWith($row, 'date')) {
                        $query .=  $row . ' LIKE \'%' . $search . '%\' OR ';
                    }
                }
            }
        }
        return $query == '' ? '' : ' AND (' . rtrim($query, 'OR ') . ') ';
    }
}

if (!function_exists('slug')) {
    function slug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

if (!function_exists('generateCode')) {
    function generate_code($field, $table, $where = '', $prefix = '', $count = 5)
    {
        $CI = &get_instance();
        $CI->load->database();

        $date = date('Y-m-d');
        $datetime = date('YmdHis');
        $sql = "
            SELECT
            IFNULL(LPAD(MAX(CAST(RIGHT({$field}, $count) AS SIGNED) + 1), $count, '0'), '" . sprintf('%0' . $count . 'd', 1) . "') AS code 
            FROM {$table}
            WHERE 1 $where
        ";
        $query = $CI->db->query($sql);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $prefix . $row->code;
        } else {
            return $prefix . '';
        }
    }
}

if (!function_exists('is_json')) {

    function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, TRUE)) && (json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE;
    }
}

if (!function_exists('template_parse')) {

    function template_parse($html)
    {
        $startTemplate = '<script  type="text/x-template" id="container-template">';
        $endTemplate = '</script>';
        $startsAtTemplate = strpos($html, $startTemplate) + strlen($startTemplate);
        $endsAtTemplate = strpos($html, $endTemplate, $startsAtTemplate);
        $resultTemplate = substr($html, $startsAtTemplate, $endsAtTemplate - $startsAtTemplate);

        $startScript = '<script>';
        $endSecript = '</script>';
        $startsAtScript = strpos($html, $startScript) + strlen($startScript);
        $endsAtScript = strpos($html, $endSecript, $startsAtScript);
        $resultScript = substr($html, $startsAtScript, $endsAtScript - $startsAtScript);

        echo json_encode(array(
            "template" => $resultTemplate,
            "script" => $resultScript,
        ));
    }
}
