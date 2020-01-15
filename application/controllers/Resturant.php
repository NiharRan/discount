<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resturant extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['title'] = 'Offer.com || Resturant List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			$data['content'] = 'admin/resturant/list';
			$this->load->view('layouts/master', $data);
		}
    }
    
    function create()
	{
		if (!$this->permission->has_permission('resturant', 'create')) {
			redirect('/auth/login/');
		} else {
			$data['title'] = 'Offer.com || Resturant List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/resturant';

			$data['content'] = 'admin/resturant/create';
			$this->load->view('layouts/master', $data);
		}
	}
}
