<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test_api extends CI_Controller {

	function index()
	{
		$data = array();
		$this->load->model('api_model');
		$get_all_date = $this->api_model->fetch_date();
		$data['date'] = $get_all_date->result();
		$get_all_role = $this->api_model->fetch_role();
		$data['role'] = $get_all_role->result();
		$this->load->view('api_view',$data);
	}

	function action()
	{
		if($this->input->post('data_action'))
		{
			$data_action = $this->input->post('data_action');
			if($data_action == "Delete")
			{
				$api_url = "http://localhost/rest/api/delete";
				$form_data = array(
					'id'		=>	$this->input->post('user_id')
				);
				$client = curl_init($api_url);
				curl_setopt($client, CURLOPT_POST, true);
				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($client);
				curl_close($client);
				echo $response;
			}

			if($data_action == "Edit")
			{
				$api_url = "http://localhost/rest/api/update";
				$form_data = array(
					'first_name'		=>	$this->input->post('first_name'),
					'last_name'			=>	$this->input->post('last_name'),
					'id'				=>	$this->input->post('user_id'),
					'email'				=>	$this->input->post('email'),
					'phone_number'		=>	$this->input->post('phone_number'),
					'role_type'			=>	$this->input->post('role_type'),
					'old_email'			=>	$this->input->post('old_email'),
					'old_phone_number'	=>	$this->input->post('old_phone_number')
				);

				$client = curl_init($api_url);
				curl_setopt($client, CURLOPT_POST, true);
				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($client);
				curl_close($client);
				echo $response;
			}

			if($data_action == "fetch_single")
			{
				$api_url = "http://localhost/rest/api/fetch_single";
				$form_data = array(
					'id'		=>	$this->input->post('user_id')
				);
				$client = curl_init($api_url);
				curl_setopt($client, CURLOPT_POST, true);
				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($client);
				curl_close($client);
				echo $response;
			}

			if($data_action == "Insert")
			{
				$api_url = "http://localhost/rest/api/insert";
				$form_data = array(
					'first_name'		=>	$this->input->post('first_name'),
					'last_name'			=>	$this->input->post('last_name'),
					'email'				=>	$this->input->post('email'),
					'phone_number'		=>	$this->input->post('phone_number'),
					'role_type'			=>	$this->input->post('role_type')
				);
				$client = curl_init($api_url);
				curl_setopt($client, CURLOPT_POST, true);
				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($client);
				curl_close($client);
				echo $response;
			}

			if($data_action == "fetch_all")
			{
				$api_url = "http://localhost/rest/api/";
				$date_added = '';
				$role_type = '';
				$date_added = $this->input->post('date_added');
				$role_type = $this->input->post('role_type');
				$client = curl_init($api_url);
				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($client);
				$result = json_decode($response);
				$output = '';
				if(isset($result))
				{
					foreach($result as $row)
					{
						if((!empty($date_added) && $row->date_added == $date_added ) && empty($role_type)){
							$output .= '
						<tr>
							<td>'.$row->first_name.'</td>
							<td>'.$row->last_name.'</td>
							<td>'.$row->email.'</td>
							<td>'.$row->phone_number.'</td>
							<td>'.$row->role_type.'</td>
							<td>'.$row->date_added.'</td>
							<td><butto type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
							<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
						</tr>

						';
						}elseif((!empty($role_type) && $row->role_type == $role_type) && empty($date_added)){
							$output .= '
						<tr>
							<td>'.$row->first_name.'</td>
							<td>'.$row->last_name.'</td>
							<td>'.$row->email.'</td>
							<td>'.$row->phone_number.'</td>
							<td>'.$row->role_type.'</td>
							<td>'.$row->date_added.'</td>
							<td><butto type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
							<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
						</tr>

						';
						}elseif((!empty($role_type) && ($row->role_type == $role_type)) && (!empty($date_added) && ($row->date_added == $date_added))){
							$output .= '
						<tr>
							<td>'.$row->first_name.'</td>
							<td>'.$row->last_name.'</td>
							<td>'.$row->email.'</td>
							<td>'.$row->phone_number.'</td>
							<td>'.$row->role_type.'</td>
							<td>'.$row->date_added.'</td>
							<td><butto type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
							<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
						</tr>

						';
						}elseif((!isset($role_type) && !isset($date_added)) || (empty($role_type) && empty($date_added))){
							$output .= '
						<tr>
							<td>'.$row->first_name.'</td>
							<td>'.$row->last_name.'</td>
							<td>'.$row->email.'</td>
							<td>'.$row->phone_number.'</td>
							<td>'.$row->role_type.'</td>
							<td>'.$row->date_added.'</td>
							<td><butto type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
							<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
						</tr>

						';
						}
						
					}
				}
				else
				{
					$output .= '
					<tr>
						<td colspan="8" align="center">No Data Found111</td>
					</tr>
					';
				}

				echo $output;
			}
		}
	}	
}

?>