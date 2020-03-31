<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('order_model');
	}

	/***
	 * @route {{ orders }}
	 * @return order list page
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
			$data['title'] = 'Offer.com || Order List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/order';
			$data['content'] = 'admin/order/list';
			$this->load->view('layouts/master', $data);
		}
	}

	/**
	 * order delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param order_id
	 * @return	bool
	 */
	function delete($order_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->order_model->remove($order_id);
		// if order deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}


	/**
	 * fetch all orders by this method. 
	 * Return order list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] order list
	 */
	function allOrders()
	{
		// take the last order id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 order from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');
		$query['restaurant_id'] = $this->input->get('restaurant_id');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in orders table according to search query
		$total_rows = $this->order_model->fetch_total_order_rows($query);
		// fetch 10 orders start from 'offset' where query
		$orders = $this->order_model->fetch_all_orders($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'orders/all/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if orders is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => order list
		 * 	 links => pagination links
		 */
		if (count($orders) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $orders;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * order action by this method. 
	 * Return TRUE if action done successfully
	 * otherwise FALSE.
	 *
	 * @param order_id
	 * @return	bool
	 */
	function changeStatus($order_id)
	{
		// initialize response data
		$jsonData = array('success' => false);
		$order_status = $this->input->post('order_status');
		$customer_id = $this->input->post('customer_id');
		// change status through this method
		$result = $this->db->set('order_status', $order_status)
						->where('order_id', $order_id)
						->update("orders");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
			// $this->send_email($order_id, $customer_id);
		}
		// send response to client
		echo json_encode($jsonData);
	}

	function send_email($order_id, $customer_id)
	{
		$customer = $this->global_model->has_one('customers', 'customer_id', $customer_id);
		$data['customer'] = $customer;
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($customer['customer_email']);
		$this->email->subject(sprintf($this->lang->line('Confirmation Message'), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/confirm_message', $data, TRUE));
		$this->email->send();
	}

	function printSingleOrder($order_id)
	{
		$query['order_id'] = $order_id;
		$data['order'] = $this->order_model->fetch_order_on_condition($query)[0];
		$this->load->view('admin/order/print', $data);
	}
    
}
