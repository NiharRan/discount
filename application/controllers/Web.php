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
		$this->load->model("offer_model");
		$this->load->model("tag_model");
	}

	function index()
	{
		$data['title'] = 'Offer.com || Eat like king';
        $data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		
		// feature restaurants
		$data['restaurants'] = $this->db->select('*')
								->from('restaurants')->where('restaurant_status', 1)
								->limit(10)->get()->result_array();

		// most viewed tags
		$data['tags'] = $this->db->select('*')
								->from('tags')->where('tag_status', 1)
								->limit(12)->get()->result_array();
		$data['popularTags'] = $data['tags'];

		$query['search'] = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
		$query['perpage'] = 10;
		$query['base_url'] = site_url('web/');

		$data['offers'] = $this->offers($query);
        
        $data['offerSlider'] = 'web/includes/slider';
        $data['popularOffer'] = 'web/includes/popular-offer';
        $data['mostPopularCategories'] = 'web/includes/most-popular-categories';
        $data['mobileApp'] = 'web/includes/mobile-app';
        $data['content'] = 'web/home';
        $this->load->view('layouts/web', $data);
	}


	function offersOfTag()
	{
		$query['tag_slug'] = $this->uri->segment(3);

		$data['popularTags'] = $this->db->select('*')
								->from('tags')->where('tag_status', 1)
								->limit(12)->get()->result_array();

		// increase visit count of tag
		$this->db->where('tag_slug', $query['tag_slug'])
				->set('visit_count', 'visit_count + 1', false)
				->update('tags');
		$data['tag'] = $this->tag_model->fetch_tag_on_condition($query);
		$restaurants = $this->restaurant_model->fetch_all_restaurants_of_tag($data['tag']['tag_id']);
		$restaurantIds = array();
		foreach ($restaurants as $restaurant) {
			array_push($restaurantIds, $restaurant['restaurant_id']);
		}

		$query['restaurant_ids'] = $restaurantIds;
		$query['search'] = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
		$query['perpage'] = 10;
		$query['base_url'] = site_url('web/category/'.$data['tag']['tag_slug']);

		$data['offers'] = $this->offers($query);
		
		$data['title'] = 'Offer.com || '.$data['tag']['tag_name'];
        $data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		// print_r($data['tag']);
        $data['content'] = 'web/offer-of-tag';
        $this->load->view('layouts/web', $data);
	}

	function searchOfferByRestaurantAndTag()
	{
		$query['restaurant'] = isset($_REQUEST['restaurant']) ? $_REQUEST['restaurant'] : '';
		$query['tag'] = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';
		$query['perpage'] = 10;
		$query['base_url'] = site_url('web/offers/search/?restaurant='.$query['restaurant'].'&tag='.$query['tag']);

		$data['offers'] = $this->offers($query);

		$data['offerSlider'] = 'web/includes/slider';
        $data['popularOffer'] = 'web/includes/popular-offer';
        $data['mostPopularCategories'] = 'web/includes/most-popular-categories';
        $data['mobileApp'] = 'web/includes/mobile-app';
        $data['content'] = 'web/home';
        $this->load->view('layouts/web', $data);
	}


	function offersOfRestaurant()
	{
		$query['restaurant_slug'] = $this->uri->segment(3);
		$data['restaurant'] = $this->restaurant_model->fetch_restaurant_on_condition($query);

		$data['popularTags'] = $this->db->select('*')
								->from('tags')->where('tag_status', 1)
								->limit(12)->get()->result_array();

		$restaurantIds = array();
		array_push($restaurantIds, $data['restaurant']['restaurant_id']);
		$query['restaurant_ids'] = $restaurantIds;
		$query['search'] = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
		$query['perpage'] = 10;
		$query['base_url'] = site_url('web/restaurant/'.$data['restaurant']['restaurant_slug']);

		$data['offers'] = $this->offers($query);
		
		$data['title'] = 'Offer.com || '.$data['restaurant']['restaurant_name'];
        $data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		// print_r($data['restaurant']);
        $data['content'] = 'web/offer-of-restaurant';
        $this->load->view('layouts/web', $data);
	}



	function offers($query)
	{
		// take the last offer id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 offer from server
		$perpage = $query['perpage'];
		$base_url = $query['base_url'];

		// response object
		$data = array('data' => array(), 'links' => '');

		// total rows in offers table according to search query
		$total_rows = $this->offer_model->fetch_total_offer_rows($query);
		// fetch 10 offers start from 'offset' where query
		$offers = $this->offer_model->fetch_all_offers($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => $base_url,
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if offers is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => offerlist
		 * 	 links => pagination links
		 */
		if (count($offers )> 0) {
			$data['data'] = $offers;
			$data['links'] = $this->custom->paginate($obj);
		}
		return $data;
	}




	function singeOffer()
	{
		$query['offer_slug'] = $this->uri->segment(3);

		$data['popularTags'] = $this->db->select('*')
								->from('tags')->where('tag_status', 1)
								->limit(12)->get()->result_array();

		// increase visit count of offer
		$this->db->where('offer_slug', $query['offer_slug'])
				->set('visit_count', 'visit_count + 1', false)
				->update('offers');
		$data['offer'] = $this->offer_model->fetch_offer_on_condition($query);

		$data['title'] = 'Offer.com || '.$data['offer']['offer_name'];
        $data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		
        $data['pageHeader'] = 'web/includes/page-header';
        $data['content'] = 'web/single';
        $this->load->view('layouts/web', $data);
	}


	function allRestaurants()
	{
		$data['featureRestaurants'] = $this->db->select('*')
			->from('restaurants')
			->where('feature_restaurant', 1)
			->get()
			->result_array();
		$keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$searchKey = '';
		if (isset($_GET['key'])) {
			$searchKey = $this->input->get('key');
			$data['restaurants'][$searchKey] = $this->restaurant_model->fetch_restaurants_by_key($searchKey);
		}else {
			foreach ($keys as $key) {
				$data['restaurants'][$key] = $this->restaurant_model->fetch_restaurants_by_key($key);
			}
		}

		foreach ($keys as $key) {
			$data['total'][$key] = $this->restaurant_model->count_restaurants_by_key($key);
		}
		
		$data['title'] = 'Offer.com || A-Z Restaurants';
        $data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		
		$data['except'] = 'sidebar';
		$data['pageHeaderCover'] = 'web/includes/page-header-cover';
        $data['content'] = 'web/restaurant-atz';
        $this->load->view('layouts/web', $data);
	}
}
