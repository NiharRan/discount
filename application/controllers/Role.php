<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('role_model');
		$this->user_id = $this->tank_auth->get_user_id();
	}

	/***
	 * @route {{ settings/roles }}
	 * @return role list page
	 * using vue
	 */
	function index()
	{
		// if user is not logged in 
		// redirect him/her to login page
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			// this is the page title
			$data['title'] = 'Offer.com || Role List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/role';
			$data['content'] = 'admin/settings/role';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new role store by this method. 
	 * Return TRUE if role is stored successfully
	 * otherwise FALSE.
	 *
	 * @param	array	(role_name, role_body)
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false);
		
		// store all roles one by one
		$data = array(
			'role_name' => $this->input->post('role_name'),
			'role_status' => 1,
			'role_created_at' => date('Y-m-d'),
			'role_creator' => $this->user_id,
		);
		// store role through role model
		$result = $this->role_model->store($data);
		// if stored successfully return true
		if($result) $jsonData['success'] = true;

		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * role info update by this method. 
	 * Return TRUE if role are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	role_name
	 * @param role_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('role_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successfull
			$jsonData['check'] = true;
			$data = array(
				'role_name' => $this->input->post('role_name'),
				'role_creator' => $this->user_id,
			);
			$role_id = $this->input->post('role_id');

			$result = $this->role_model->update($data, $role_id);

			// if data updated make success true
			if ($result) {
				$jsonData['success'] = true;
			}
		}else {
			// return formvalidation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = form_error($key);
			}
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * role delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param role_id
	 * @return	bool
	 */
	function delete($role_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->role_model->remove($role_id);
		// if role deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * this method check user has permission to perform role action
	 * @param role_id
	 * @param role_creator
	 * @return	object
	 */
	function has_permission_to_action_role()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$role_creator = $this->input->post('role_creator');
		$role_id = $this->input->post('role_id');
		$action = $this->input->post('action');
		if ($this->permission->has_permission('role', $action)) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * role status change by this method. 
	 * Return TRUE if status changed successfully
	 * otherwise FALSE.
	 *
	 * @param role_id
	 * @return	bool
	 */
	function changeStatus()
	{
		$role_status = $this->input->post('role_status');
		$role_id = $this->input->post('role_id');
		// response array
		$jsonData = array('success' => false);
		$result = $this->db->set('role_status', $role_status == 1 ? 0 : 1)
						->where('role_id', $role_id)
						->update("roles");
		// if role status changed successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all roles by this method. 
	 * Return rolelist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] rolelist
	 */
	function allroles()
	{
		// take the last role id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 role from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in roles table according to search query
		$total_rows = $this->role_model->fetch_total_role_rows($query);
		// fetch 10 roles start from 'offset' where query
		$roles = $this->role_model->fetch_all_roles($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'role/allroles/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if roles is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => rolelist
		 * 	 links => pagination links
		 */
		if (count($roles) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $roles;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active roles by this method. 
	 * Return rolelist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] rolelist
	 */
	function allactiveroles()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$roles = $this->role_model->fetch_all_active_roles();
		/**
		 * if roles is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => rolelist
		 */
		if (count($roles) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $roles;
		}
		// responde send
		echo json_encode($jsonData);
	}
    
}
