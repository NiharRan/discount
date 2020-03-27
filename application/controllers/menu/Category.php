<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('menu/category_model');
		$this->load->model('global_model');
	}

	/***
	 * @route {{ menu/categories }}
	 * @return category list page
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
			$data['title'] = 'Offer.com || Category List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/menu/category';
			$data['content'] = 'admin/menu/category';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new category store by this method. 
	 * Return TRUE if categories are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	stringArray	(category_names [single or multiple])
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false);
		$category_name = $this->input->post('category_name');

		// store all categories one by one
		$data = array(
			'category_name' => $category_name,
			'category_slug' => url_title($category_name),
			'category_status' => 1,
			'category_doc' => date('Y-m-d H:i:s'),
			'category_creator' => $this->tank_auth->get_user_id()
		);
		// store category through category model
		$result = $this->category_model->store($data);
		// if stored successfully return true
		if($result) $jsonData['success'] = true;
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * category info update by this method. 
	 * Return TRUE if category are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	category_name
	 * @param category_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('category_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get category name and id
			$data['category_name'] = $this->input->post('category_name');
			$data['category_dom'] = date('Y-m-d H:i:s');
			$data['category_slug'] = url_title($this->input->post('category_name'));
			$category_id = $this->input->post('category_id');

			$result = $this->category_model->update($data, $category_id);

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
	 * category delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param category_id
	 * @return	bool
	 */
	function delete($category_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->category_model->remove($category_id);
		// if category deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all categories by this method. 
	 * Return category list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] category list
	 */
	function allCategories()
	{
		// take the last category id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 category from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in categories table according to search query
		$total_rows = $this->category_model->fetch_total_category_rows($query);
		// fetch 10 categories start from 'offset' where query
		$categories = $this->category_model->fetch_all_categories($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'menu/categories/all/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if categories is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => category list
		 * 	 links => pagination links
		 */
		if (count($categories) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $categories;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active categories by this method. 
	 * Return category list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] category list
	 */
	function allActiveCategories()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$categories = $this->category_model->fetch_all_active_categories();
		/**
		 * if categories is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => category list
		 */
		if (count($categories) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $categories;
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * category status change by this method. 
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
		$category_status = $this->input->post('category_status');
		$category_id = $this->input->post('category_id');
		// change status through this method
		$result = $this->db->set('category_status', $category_status == 1 ? 0 : 1)
						->where('category_id', $category_id)
						->update("categories");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}
    
}
