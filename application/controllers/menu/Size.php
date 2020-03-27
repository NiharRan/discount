<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Size extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('menu/size_model');
		$this->load->model('global_model');
	}

	/***
	 * @route {{ menu/sizes }}
	 * @return size list page
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
			$data['title'] = 'Offer.com || Product Size List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/menu/size';
			$data['content'] = 'admin/menu/size';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new size store by this method. 
	 * Return TRUE if sizes are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	string food_size_name
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false);
		// convert sizes from string to array
		$food_size_name = $this->input->post('food_size_name');

		// store all sizes one by one
		$data = array(
			'food_size_name' => $food_size_name,
			'food_size_status' => 1,
			'food_size_doc' => date('Y-m-d'),
			'food_size_creator' => $this->tank_auth->get_user_id()
		);
		// store size through size model
		$result = $this->size_model->store($data);
		// if stored successfully return true
		if($result) $jsonData['success'] = true;
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * size info update by this method. 
	 * Return TRUE if size are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	food_size_name
	 * @param food_size_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('food_size_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get size name and id
			$data['food_size_name'] = $this->input->post('food_size_name');
			$data['food_size_dom'] = date('Y-m-d H:i:s');
			$food_size_id = $this->input->post('food_size_id');

			$result = $this->size_model->update($data, $food_size_id);

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
	 * size delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param food_size_id
	 * @return	bool
	 */
	function delete($food_size_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->size_model->remove($food_size_id);
		// if size deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all sizes by this method. 
	 * Return size list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] size list
	 */
	function allSizes()
	{
		// take the last size id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 size from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in sizes table according to search query
		$total_rows = $this->size_model->fetch_total_food_size_rows($query);
		// fetch 10 sizes start from 'offset' where query
		$sizes = $this->size_model->fetch_all_sizes($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'menu/food-sizes/all/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if sizes is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => size list
		 * 	 links => pagination links
		 */
		if (count($sizes) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $sizes;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active sizes by this method. 
	 * Return size list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] size list
	 */
	function allActiveSizes()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$sizes = $this->size_model->fetch_all_active_sizes();
		/**
		 * if sizes is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => size list
		 */
		if (count($sizes) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $sizes;
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * size status change by this method. 
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
		$food_size_status = $this->input->post('food_size_status');
		$food_size_id = $this->input->post('food_size_id');
		// change status through this method
		$result = $this->db->set('food_size_status', $food_size_status == 1 ? 0 : 1)
						->where('food_size_id', $food_size_id)
						->update("sizes");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}
    
}
