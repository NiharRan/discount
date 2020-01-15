<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deshboard extends CI_Controller {

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
			$data['title'] = 'Offer.com || Deshboard';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			$data['content'] = 'admin/deshboard';
			$this->load->view('layouts/master', $data);
		}
	}
}
