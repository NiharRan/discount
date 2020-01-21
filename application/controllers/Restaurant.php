<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('form_validation');
		$this->load->helper('security');
		$this->load->library('permission');
		$this->lang->load('tank_auth');
		$this->load->model('restaurant_model');
		$this->user_id = $this->tank_auth->get_user_id();
		$this->username = $this->tank_auth->get_username();
	}

	/***
	 * @route {{ restaurants }}
	 * @return restaurant list page
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
			$data['title'] = 'Offer.com || Users';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
            
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/restaurant/list';
			$data['content'] = 'admin/restaurant/list';
			$this->load->view('layouts/master', $data);
		}
    }

	/***
	 * this method create new restaurant
	 * @route {{ restaurants/create }}
	 * @return restaurant info
	 * using vue
	 */
	function profile()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['title'] = 'Offer.com || Restaurant List';
			$data['user_id']	= $this->user_id;
			$data['username']	= $this->username;

			$data['restaurants'] = $this->restaurant_model->fetch_restaurants_by_user_info($this->user_id);
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/restaurant/edit';
			$data['content'] = 'admin/restaurant/edit';
			$this->load->view('layouts/master', $data);
		}
    }
	
	/**
	 * new restaurant info store by this method. 
	 * if restaurant are stored successfully
	 * 	Return JSON object with success status true
	 * otherwise errors and success status false.
	 *
	 * @param	stringArray	
	 * @return	object
	 */
    function create()
	{
		if (!$this->permission->has_permission('restaurant', 'create')) {
			redirect('/auth/login/');
		} else {
			$data['title'] = 'Offer.com || Restaurant List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/restaurant/create';

			$data['content'] = 'admin/restaurant/create';
			$this->load->view('layouts/master', $data);
		}
	}
}
