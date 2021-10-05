<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
--------------------------------------------------------------------------------
--------------------------------------------------------------------------------
template_helper.php
CodeIgniter Template Library
Hubert Ursua
https://github.com/hyubs/CodeIgniter-Template-Library
--------------------------------------------------------------------------------
--------------------------------------------------------------------------------
*/

class Template 
{
	private $CI;
	private $template_params;
	
	public function __construct() 
	{
		$this->CI =& get_instance();
		$this->template_params = array();

	}
	
	// Sets the content of a template position
	public function set($position, $data, $append = true)
	{
		if(!isset($this->template_params[$position]) || $append === false)
		{
			$this->template_params[$position] = $data;
		}
		else
		{
			$this->template_params[$position] .= $data;
		}
	}
	
	// Gets the content of a template position
	public function get($position)
	{
		if(isset($this->template_params[$position]))
		{
			return $this->template_params[$position];
		}
		else
		{
			return '';
		}
	}
	
	// Sets the title of the page
	public function title($title = '')
	{
		$this->template_params['title'] = $title;
	}
	
	// Sets the content of the page
	public function content($view, $params = array(), $position = 'content', $append = true) 
	{
		$data = $this->CI->load->view($view, $params, true);
		$this->set($position, $data, $append);
	}

	// Displays the page using the parameters preset using the set methods of this class
	public function show($template_view = 'template', $return_string = true) 
	{		
		$complete_page = $this->CI->load->view($template_view, null, true);
		
		if($return_string == true)
		{
			echo $complete_page;
		}
		else
		{
			return $complete_page;
		}
	}

	public function component_client($component_name,  $params = array()){
		if(is_array($component_name)){
			$data = '';
			foreach ($component_name as $key => $v) {
				$data .= "\n";
				$data .= $this->CI->load->view('client/'.TEMPLATE_NAME.'/component/'.$key,$v, true);
			}

		} else {
			$data = $this->CI->load->view('client/'.TEMPLATE_NAME.'/component/'.$component_name,$params, true);
			return $data;
		}
		
	}

	public function component_admin($component_name,  $params = array()){
		if(is_array($component_name)){
			$data = '';
			foreach ($component_name as $key => $v) {
				$data .= "\n";
				$data .= $this->CI->load->view('admin/component/'.$component_name,$params, true);

			}

		} else {
			$data = $this->CI->load->view('admin/component/'.$component_name,$params, true);
			return $data;
		}
	}

	public function _app_config($params = array()){

		$default_config = [
			'site_url' => site_url(),
			'gateway_url' => site_url() . 'gateway/',
			'assets_url' => site_url() . 'assets/',
			'theme_url' => TEMPLATE_CLIENT_URL,
		];

		$default_config['query'] = $this->CI->uri->segment_array();
		if(empty($default_config['query'])){
			$default_config['query'] = (object)[];
		}
		$default_config['params'] = $this->CI->input->get();
		if(empty($default_config['params'])){
			$default_config['params'] = (object)[];
		}

		$config = array_merge($default_config, $params);
		echo json_encode($config);
	}
	
}
