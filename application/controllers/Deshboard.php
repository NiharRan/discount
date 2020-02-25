<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deshboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->model('global_model');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('web');
		} else {
			$data['title'] = 'Offer.com || Deshboard';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();

			$data['totalRestaurant'] = $this->global_model->total_rows('restaurants');
			$data['totalUser'] = $this->global_model->total_rows('users');
			$data['totalTemplate'] = $this->global_model->total_rows('templates');
			$data['totalOffer'] = $this->global_model->total_rows('offers');

			$query['single_date'] = date('Y-m-d');
			$query['limit'] = 10;
			$data['runningOffers'] = $this->offer_model->fetch_offer_on_condition($query);
			$data['popularTags'] = $this->tag_model->fetch_all_tags(10, 0, array('search' => ''));
			
			$data['content'] = 'admin/deshboard';
			$this->load->view('layouts/master', $data);
		}
	}
}
