<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aditional extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('menu/aditional_model');
		$this->load->model('global_model');
	}

	/***
	 * @route {{ menu/foods }}
	 * @return food list page
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
			$data['title'] = 'Offer.com || Food Aditional List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/menu/food_aditional';
			$data['content'] = 'admin/menu/food_aditional';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new food store by this method. 
	 * Return TRUE if foods are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	food_aditional_weight
	 * @param	food_aditional_name 
	 * @param	food_aditional_price 
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('food_aditional_price', 'Price',  'required');
		$this->form_validation->set_rules('food_aditional_weight', 'Weight',  'required');
		$this->form_validation->set_rules('food_aditional_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get size name and id
			$data['food_aditional_name'] = $this->input->post('food_aditional_name');
			$data['food_aditional_price'] = $this->input->post('food_aditional_price');
			$data['food_aditional_weight'] = $this->input->post('food_aditional_weight');
            $data['food_aditional_doc'] = date('Y-m-d H:i:s');
            $data['food_aditional_creator'] = $this->tank_auth->get_user_id();

			$result = $this->aditional_model->store($data);

			// if data updated make success true
			if ($result) {
				$jsonData['success'] = true;
			}
		}else {
			// return form_validation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
        }
        
        // send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * size info update by this method. 
	 * Return TRUE if size are updated successfully
	 * otherwise FALSE.
	 * @param	food_aditional_id 
	 * @param	food_aditional_weight 
	 * @param	food_aditional_name 
	 * @param	food_aditional_price 
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('food_aditional_name', 'Name',  'required');
		$this->form_validation->set_rules('food_aditional_price', 'Price',  'required');
		$this->form_validation->set_rules('food_aditional_weight', 'Weight',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get size name and id
			$data['food_aditional_name'] = $this->input->post('food_aditional_name');
			$data['food_aditional_price'] = $this->input->post('food_aditional_price');
			$data['food_aditional_weight'] = $this->input->post('food_aditional_weight');
			$data['food_aditional_dom'] = date('Y-m-d H:i:s');
			$food_aditional_id = $this->input->post('food_aditional_id');

			$result = $this->aditional_model->update($data, $food_aditional_id);

			// if data updated make success true
			if ($result) {
				$jsonData['success'] = true;
			}
		}else {
			// return form_validation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
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
	 * @param food_aditional_id
	 * @return	bool
	 */
	function delete($food_aditional_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->aditional_model->remove($food_aditional_id);
		// if size deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all foods by this method. 
	 * Return size list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] size list
	 */
	function allFoodAditionals()
	{
		// take the last size id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 size from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in food_aditionals table according to search query
		$total_rows = $this->aditional_model->fetch_total_food_aditional_rows($query);
		// fetch 10 food_aditionals start from 'offset' where query
		$food_aditionals = $this->aditional_model->fetch_all_food_aditionals($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'menu/food-prices/all/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if food_aditionals is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => size list
		 * 	 links => pagination links
		 */
		if (count($food_aditionals) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $food_aditionals;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active FoodPrices by this method. 
	 * Return size list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] size list
	 */
	function allActiveFoodAditinals()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$food_adtionals = $this->aditional_model->fetch_all_active_food_aditionals();
		/**
		 * if food_aditionals is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => size list
		 */
		if (count($food_aditionals) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $food_aditionals;
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
		$food_aditional_status = $this->input->post('food_aditional_status');
		$food_aditional_id = $this->input->post('food_aditional_id');
		// change status through this method
		$result = $this->db->set('food_aditional_status', $food_aditional_status == 1 ? 0 : 1)
						->where('food_aditional_id', $food_aditional_id)
						->update("food_aditionals");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}
    
}
