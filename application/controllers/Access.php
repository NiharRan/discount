<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('permission_model');
	}

	/***
	 * @route {{ settings/permissions }}
	 * @return permission list page
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
			$data['title'] = 'Offer.com || Permission List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/permission';
			$data['content'] = 'admin/settings/permission';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new permission store by this method. 
	 * Return TRUE if permissions are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	model_name
	 * @param	role_id
	 * @return	{check, success, errors}
	 */
	function store()
	{
		// response array
		$jsonData = array('check' => false, 'success' => false, 'errors' => array());
		$rules = array(
			array('field' => 'model_name', 'label' => 'Module', 'rules' => 'required'),
			array('field' => 'role_id', 'label' => 'Role', 'rules' => 'required'),
		);
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$jsonData['check'] = true;
			// convert permissions from string to array
			$actions = array('create', 'edit', 'delete', 'list-view');

			foreach ($actions as $key => $value) {
				// store all actions one by one
				$data = array(
					'role_id' => $this->input->post('role_id'),
					'model_name' => $this->input->post('model_name'),
					'action' => $actions[$key],
					'permission_created_at' => date('Y-m-d'),
					'permission_creator' => $this->tank_auth->get_user_id()
				);
				// store permission through permission model
				$result = $this->permission_model->store($data);
				// if stored successfully return true
				if($result) $jsonData['success'] = true;
				else $jsonData['success'] = false;
			}
		} else {
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}
		
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * permission info update by this method. 
	 * Return TRUE if permission are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	model_name
	 * @param	action
	 * @param	role_id
	 * @param permission_id
	 * @return	{check, success, errors}
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$rules = array(
			array('field' => 'model_name', 'label' => 'Module', 'rules' => 'required'),
			array('field' => 'action', 'label' => 'Action', 'rules' => 'required'),
			array('field' => 'role_id', 'label' => 'Role', 'rules' => 'required'),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get permission name and id
			$data['model_name'] = $this->input->post('model_name');
			$data['role_id'] = $this->input->post('role_id');
			$data['action'] = $this->input->post('action');
			$data['permission_updated_at'] = date('Y-m-d H:i:s');
			$permission_id = $this->input->post('permission_id');

			$result = $this->permission_model->update($data, $permission_id);

			// if data updated make success true
			if ($result) {
				$jsonData['success'] = true;
			}
		}else {
			// return form validation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * permission delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param permission_id
	 * @return	bool
	 */
	function delete($permission_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->permission_model->remove($permission_id);
		// if permission deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all permissions by this method. 
	 * Return permissionList if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] permissionList
	 */
	function allpermissions()
	{
		// take the last permission id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 permission from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in permissions table according to search query
		$total_rows = $this->permission_model->fetch_total_permission_rows($query);
		// fetch 10 permissions start from 'offset' where query
		$permissions = $this->permission_model->fetch_all_permissions($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'access/allpermissions/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if permissions is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => permissionList
		 * 	 links => pagination links
		 */
		if (count($permissions) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $permissions;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active permissions by this method. 
	 * Return permissionList if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] permissionList
	 */
	function allactivepermissions()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$permissions = $this->permission_model->fetch_all_active_permissions();
		/**
		 * if permissions is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => permissionList
		 */
		if (count($permissions) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $permissions;
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * permission status change by this method. 
	 * if status change
	 * Return success true 
	 * otherwise false.
	 *
	 * @return	true/false
	 */
	function changeStatus()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$permission_status = $this->input->post('permission_status');
		$permission_id = $this->input->post('permission_id');
		// change status through this method
		$result = $this->db->set('permission_status', $permission_status == 1 ? 0 : 1)
						->where('permission_id', $permission_id)
						->update("permissions");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active permissions of user by this method. 
	 * Return permissionList if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] permissionList
	 */
	function userActivePermissions()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$permissions = $this->permission_model->fetch_user_permissions();
		/**
		 * if permissions is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => permissionList
		 */
		if (count($permissions) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $permissions;
		}
		// responde send
		echo json_encode($jsonData);
	}
    
}
