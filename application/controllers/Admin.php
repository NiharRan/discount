<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('form_validation');
		$this->load->helper('security');
		$this->load->library('permission');
		$this->lang->load('tank_auth');
		$this->load->model('user_model');
	}

    /***
	 * @route {{ settings/tags }}
	 * @return tag list page
	 * using vue
	 */
	function profile($username='')
	{
        // if user is not logged in 
		// redirect him/her to login page
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
            // this is the page title
			$data['title'] = 'Offer.com || '.$this->session->userdata("name");
			$data['user_id']	= $this->tank_auth->get_user_id();
            $data['username']	= $this->tank_auth->get_username();
            
            // fetch user info by username
            $query['username'] = $username;
            $data['userInfo'] = $this->user_model->fetch_user_info_on_condition($query);
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/user/profile';
			$data['content'] = 'admin/profile';
			$this->load->view('layouts/master', $data);
		}
    }

    /**
	 * this method fetch user info 
	 * using user name
	 * @return user info
	 */
	function profile_info()
	{
		// intialize response data
		$jsonData = array('success' => false, 'data' => '');

		// get user name from url
		$query['username'] = $this->uri->segment(3);
		// fetch user info
		$user = $this->user_model->fetch_user_info_on_condition($query);

		// if user info found
		if (count($user) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $user;
		}
		// send response data to client
		echo json_encode($jsonData);
	}
}