<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model("global_model");
		$this->load->model("menu/category_model");
		$this->load->model("menu/aditional_model");
	}

	function index()
	{
		$query = $this->category_model->fetch_all_active_categories();
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
        $data['title'] = 'Soup - Restaurant with Online Ordering System';
        $this->load->view('web/menu/index', $data);
    }


    function checkout()
	{
        $data['title'] = 'Soup - Checkout';
        $this->load->view('web/menu/checkout', $data);
    }

}