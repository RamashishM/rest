<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
@ini_set('display_errors', 0);
class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model');
		$this->load->library('form_validation');
	}

	function index()
	{	
		$data = $this->api_model->fetch_all();		
		echo json_encode($data->result_array());
	}

	function insert()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');		
		$this->form_validation->set_rules('email', 'Email', 'required|is_unique[tbl_sample.email]');
		$this->form_validation->set_rules('phone_number', 'Phone number', 'required|is_unique[tbl_sample.phone_number]');
		$this->form_validation->set_rules('role_type', 'Role type', 'required');
		if($this->form_validation->run())
		{
			$data = array(
				'first_name'	=>	$this->input->post('first_name'),
				'last_name'		=>	$this->input->post('last_name'),
				'email'			=>	$this->input->post('email'),
				'phone_number'	=>	$this->input->post('phone_number'),
				'role_type'		=>	$this->input->post('role_type'),
				'date_added'	=> date('d-m-Y')
			);

			$this->api_model->insert_api($data);

			$array = array(
				'success'		=>	true
			);
		}
		else
		{
			$array = array(
				'error'					=>	true,
				'first_name_error'		=>	form_error('first_name'),
				'last_name_error'		=>	form_error('last_name'),
				'email_error'			=>	form_error('email'),
				'phone_number_error'	=>	form_error('phone_number'),
				'role_type_error'		=>	form_error('role_type')
			);
		}
		echo json_encode($array);
	}
	
	function fetch_single()
	{
		if($this->input->post('id'))
		{
			$data = $this->api_model->fetch_single_user($this->input->post('id'));
			foreach($data as $row)
			{
				$output['first_name'] 	= $row['first_name'];
				$output['last_name'] 	= $row['last_name'];
				$output['email'] 		= $row['email'];
				$output['phone_number'] = $row['phone_number'];
				$output['role_type'] 	= $row['role_type'];
			}
			echo json_encode($output);
		}
	}

	function update()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');

		if($this->input->post('email') != $this->input->post('old_email')) {
		   $is_unique1 =  '|is_unique[tbl_sample.email]';
		} else {
		   $is_unique1 =  '';
		}
		if($this->input->post('phone_number') != $this->input->post('old_phone_number')) {
		   $is_unique2 =  '|is_unique[tbl_sample.phone_number]';
		} else {
		   $is_unique2 =  '';
		}

		$this->form_validation->set_rules('email', 'Email', 'required'.$is_unique1);
		$this->form_validation->set_rules('phone_number', 'Phone number', 'required'.$is_unique2);
		$this->form_validation->set_rules('role_type', 'Role type', 'required');
		if($this->form_validation->run())
		{	
			$data = array(
				'first_name'		=>	$this->input->post('first_name'),
				'last_name'			=>	$this->input->post('last_name'),
				'email'				=>	$this->input->post('email'),
				'phone_number'		=>	$this->input->post('phone_number'),
				'role_type'			=>	$this->input->post('role_type')
			);

			$this->api_model->update_api($this->input->post('id'), $data);
			$array = array(
				'success'		=>	true
			);
		}
		else
		{
			$array = array(
				'error'				=>	ture,
				'first_name_error'	=>	form_error('first_name'),
				'last_name_error'	=>	form_error('last_name'),
				'email_error'		=>	form_error('email'),
				'phone_number_error'=>	form_error('phone_number'),
				'role_type_error'	=>	form_error('role_type')
			);
		}
		echo json_encode($array);
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			if($this->api_model->delete_single_user($this->input->post('id')))
			{
				$array = array(
					'success'	=>	true
				);
			}
			else
			{
				$array = array(
					'error'		=>	true
				);
			}
			echo json_encode($array);
		}
	}
}

?>