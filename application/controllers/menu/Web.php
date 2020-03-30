<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('form_validation');
		$this->load->library('custom');
		$this->load->model("global_model");
		$this->load->model("menu/category_model");
		$this->load->model("menu/aditional_model");
		$this->load->model("order_model");
	}

	function index()
	{
		$data['restaurant_slug'] = $this->uri->segment(2);
        $data['title'] = 'Soup - Restaurant with Online Ordering System';
        $this->load->view('web/menu/index', $data);
	}
	
	function fetch_categories()
	{
		$restaurant_slug = $this->input->post('restaurant_slug');
		$query = $this->category_model->fetch_all_active_categories($restaurant_slug);
		foreach ($query as $key => $category) {
			$foods = $this->global_model->has_many('foods', 'category_id', $category['category_id']);
			foreach ($foods as $k => $food) {
				$food_prices = $this->global_model->has_many('food_prices', 'food_id', $food['food_id']);
				foreach ($food_prices as $ke => $food_price) {
					$food_prices[$ke]['food_size'] = $this->global_model->has_one('food_sizes', 'food_size_id', $food_price['food_size_id']);
				}
				$foods[$k]['food_prices'] = $food_prices;
				$foods[$k]['food_tags'] = $this->global_model->belong_to_many('food_tags', 'menu_tags', 'food_id', $food['food_id'], 'menu_tag_id');
			}
			$query[$key]['foods'] = $foods;
		}
		$data['categories'] = $query;
		$data['aditionals'] = $this->aditional_model->fetch_all_active_food_aditionals();
		echo json_encode($data);
	}


    function checkout()
	{
		$data['restaurant_slug'] = $this->uri->segment(2);
        $data['title'] = 'Soup - Checkout';
        $this->load->view('web/menu/checkout', $data);
	}
	

	public function storeCustomerInfo()
	{
		$jsonData = array('errors' => array(), 'check' => false, 'success' => false, 'id' => '');
		$rules = array(
			array('field' => 'customer_name', 'label' => 'Name', 'rules' => 'required'),
			array('field' => 'customer_surname', 'label' => 'Surname', 'rules' => 'required'),
			array('field' => 'customer_street_no', 'label' => 'Street No.', 'rules' => 'required'),
			array('field' => 'customer_city', 'label' => 'City', 'rules' => 'required'),
			array('field' => 'customer_phone', 'label' => 'Contact No.', 'rules' => 'required'),
			array('field' => 'order_priority', 'label' => 'Priority', 'rules' => 'required'),
			array('field' => 'payment_type', 'label' => 'Payment Type', 'rules' => 'required'),
			array('field' => 'customer_email', 'label' => 'Email', 'rules' => 'required')
		);

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$jsonData['check'] = true;

			$data = array(
				'customer_name' => $this->input->post('customer_name'),
				'customer_surname' => $this->input->post('customer_surname'),
				'customer_street_no' => $this->input->post('customer_street_no'),
				'customer_city' => $this->input->post('customer_city'),
				'customer_phone' => $this->input->post('customer_phone'),
				'customer_email' => $this->input->post('customer_email'),
			);
			$id = $this->order_model->store_customer_info($data);
			if ($id) {
				$jsonData['success'] = true;
				$jsonData['id'] = $id;
			}
		} else {
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}
		// return response to client
		echo json_encode($jsonData); 
	}


	public function storeOrderInfo()
	{
		$jsonData = array('id' => '');
		$data = array(
			'customer_id' => $this->input->post('customer_id'),
			'total_price' => $this->input->post('total_price'),
			'order_description' => $this->input->post('order_description'),
			'order_priority' => $this->input->post('order_priority'),
			'payment_type' => $this->input->post('payment_type'),
			'restaurant_id' => $this->input->post('restaurant_id'),
			'order_doc' => date('Y-m-d H:i:s')
		);
		$id = $this->order_model->store($data);
		if ($id) {
			$jsonData['id'] = $id;
		}
		// return response to client
		echo json_encode($jsonData); 
	}


	public function storeOrderItemInfo()
	{
		$jsonData = array('id' => '');
		$data = array(
			'order_id' => $this->input->post('order_id'),
			'food_price_id' => $this->input->post('food_price_id'),
			'food_aditional_id' => $this->input->post('food_aditional_id'),
			'food_aditional_price' => $this->input->post('food_aditional_price'),
		);
		$id = $this->order_model->store_order_item_info($data);
		if ($id) {
			$jsonData['id'] = $id;
		}
		// return response to client
		echo json_encode($jsonData); 
	}

}