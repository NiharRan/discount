<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('menu/tag_model');
	}

	/***
	 * @route {{ menu/menu_tags }}
	 * @return tag list page
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
			$data['title'] = 'Offer.com || Tag List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/menu/tag';
			$data['content'] = 'admin/menu/tag';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new tag store by this method. 
	 * Return TRUE if menu_tags are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	stringArray	(menu_tag_names [single or multiple])
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false);
		// convert menu_tags from string to array
		$menu_tags = explode(',', $_POST['menu_tags']);

		foreach ($menu_tags as $key => $value) {
			// store all menu_tags one by one
			$data = array(
				'menu_tag_name' => $menu_tags[$key],
				'menu_tag_slug' => url_title($menu_tags[$key]),
				'menu_tag_status' => 1,
				'menu_tag_created_at' => date('Y-m-d')
			);
			// store tag through tag model
			$result = $this->tag_model->store($data);
			// if stored successfully return true
			if($result) $jsonData['success'] = true;
			else $jsonData['success'] = false;
		}
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * tag info update by this method. 
	 * Return TRUE if tag are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	menu_tag_name
	 * @param menu_tag_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('menu_tag_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get tag name and id
			$data['menu_tag_name'] = $this->input->post('menu_tag_name');
			$data['menu_tag_slug'] = url_title($this->input->post('menu_tag_name'));
			$menu_tag_id = $this->input->post('menu_tag_id');

			$result = $this->tag_model->update($data, $menu_tag_id);

			// if data updated make success true
			if ($result) {
				$jsonData['success'] = true;
			}
		}else {
			// return form_validation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = form_error($key);
			}
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * tag delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param menu_tag_id
	 * @return	bool
	 */
	function delete($menu_tag_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->tag_model->remove($menu_tag_id);
		// if tag deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all menu_tags by this method. 
	 * Return tag list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] tag list
	 */
	function allMenuTags()
	{
		// take the last tag id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 tag from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in menu_tags table according to search query
		$total_rows = $this->tag_model->fetch_total_menu_tag_rows($query);
		// fetch 10 menu_tags start from 'offset' where query
		$menu_tags = $this->tag_model->fetch_all_menu_tags($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'tag/allmenu_Tags/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if menu_tags is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => tag list
		 * 	 links => pagination links
		 */
		if (count($menu_tags) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $menu_tags;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active menu_tags by this method. 
	 * Return tag list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] tag list
	 */
	function allActiveMenuTags()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$menu_tags = $this->tag_model->fetch_all_active_menu_tags();
		/**
		 * if menu_tags is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => tag list
		 */
		if (count($menu_tags) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $menu_tags;
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * tag status change by this method. 
	 * if status change
	 * Return success true 
	 * otherwise false.
	 *
	 * @return	true/false
	 */
	function changeStatus()
	{
		// initialize response data
		$jsonData = array('success' => false);
		$menu_tag_status = $this->input->post('menu_tag_status');
		$menu_tag_id = $this->input->post('menu_tag_id');
		// change status through this method
		$result = $this->db->set('menu_tag_status', $menu_tag_status == 1 ? 0 : 1)
						->where('menu_tag_id', $menu_tag_id)
						->update("menu_tags");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}
    
}
