<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
			$data['vuecomponent'] = 'components/user/list';
			$data['content'] = 'admin/user/list';
			$this->load->view('layouts/master', $data);
		}
    }
	
	/**
	 * new user info store by this method. 
	 * if user are stored successfully
	 * 	Return JSON object with success status true
	 * otherwise errors and success status false.
	 *
	 * @param	stringArray	
	 * @return	object
	 */
    function create()
	{
		/**
		 * current user has not permission to create
		 * then redirect back
		 */
		if (!$this->permission->has_permission('user', 'create')) {
			redirect($_SERVER['HTTP_REFERER']);
		} else {
            // this is the page title
			$data['title'] = 'Offer.com || Create New User';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
            
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/user/create';
			$data['content'] = 'admin/user/create';
			$this->load->view('layouts/master', $data);
		}
	}

	/**
	 * new user store by this method. 
	 * Return TRUE if user info stored successfully
	 * otherwise FALSE.
	 *
	 * @param	stringArray	{{name, contact_number, email, user_type}}
	 * @return	object
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		// create validation rules array
		$rules = array(
			array('field' => 'name', 'label' => 'User Name', 'rules' => 'required'),
			array('field' => 'contact_number', 'label' => 'User Contact No.', 'rules' => 'required'),
			array('field' => 'email', 'label' => 'User E-mail', 'rules' => 'required'),
			array('field' => 'user_type', 'label' => 'User Type', 'rules' => 'required'),
		);
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			// store user info
			$data = array(
				'name' => $this->input->post('name'),
				'username' => $this->random_username(), // create unique username
				'contact_number' => $this->input->post('contact_number'),
				'email' => $this->input->post('email'),
				'user_type' => $this->input->post('user_type'),
				'activated' => 1,
				'created' => date('Y-m-d'),
				'password' => $this->tank_auth->create_password("123456")
			);
			// store user through user model
			$id = $this->user_model->store($data);

			if ($id) {
				$jsonData['success'] = true;
			}

			// if stored successfully return user id
			// if($id) {
			// 	// fetch user info using user id
			// 	$userInfo = $this->user_model->fetch_user_info_by_id($id);
			// 	$data['name'] = $userInfo->name;
			// 	$data['username'] = $userInfo->username;
			// 	$data['id'] = $userInfo->id;
			// 	$data['new_email_key'] = $userInfo->new_email_key;
			// 	$data['name'] = '123456';
			// 	// then send email to user mail
			// 	$result = $this->send_email($data);
			// 	if ($result) {
			// 		$jsonData['success'] = true;
			// 	}
			// }
			else $jsonData['success'] = false;
		} else {
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}
		
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * this method create random username
	 * from user name, contact_number and email
	 * @return string username
	 */
	function random_username()
	{
		$characters = $this->input->post('name')
					 .$this->input->post('contact_number')
					 .$this->input->post('email');
		$username = "";
    	for($i = 0; $i < 6; $i++){
        	$username .= $characters[mt_rand(0,strlen($characters) - 1)];
        }
        return $username;
	}

	/**
	 * this method will send emial to user emial
	 * @param object user info
	 * @return boolean true/false
	 */
	function send_email($userdata)
	{
		$data['user'] = $userdata;

		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($userdata['email']);
		$this->email->subject("Confirmation Message");
		$this->email->message($this->load->view('email/confirmation_message', $data, TRUE));

		if ($this->email->send()) {
			return true;
		}
		return false;
	}

	/**
	 * this method fetch all user types from server 
	 * and send them to client
	 * @return Array userTypes
	 */
	function usertypes()
	{
		// response array
		$jsonData = array('success' => false, 'data' => array());
		$usertypes = $this->user_model->fetch_all_active_user_types();
		// if usertypes table is not empty
		if ($usertypes->num_rows() > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $usertypes->result();
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all users by this method. 
	 * Return userlist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] userlist
	 */
	function allusers()
	{
		// take the last user id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 user from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in users table according to search query
		$total_rows = $this->user_model->fetch_total_user_rows($query);
		// fetch 10 users start from 'offset' where query
		$users = $this->user_model->fetch_all_users($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'users/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if users is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => userlist
		 * 	 links => pagination links
		 */
		if ($users->num_rows() > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $users->result();
			$jsonData['links'] = $this->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	function paginate($obj)
	{
		// integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_link'] = '<i class="fas fa-angle-left"></i>';
		$config['first_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_link'] = '<i class="fas fa-angle-double-right"></i>';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_link'] = '<i class="fas fa-angle-right"></i>';
		$config['last_tag_close'] = '</li>';
		
        $config['cur_tag_open'] = '<li class="active page-item"><span><b>';
		$config['cur_tag_close'] = '</b></span></li>';
		
		// set these mostly...
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'offset';
		$config['reuse_query_string'] = TRUE;


		// custom config
		$config['base_url'] = $obj['base_url'];
		$config['total_rows'] = $obj['total_rows'];
		$config['per_page'] = $obj['per_page'];
		$config['uri_segment'] = $obj['uri_segment'];

		// link tag class [ <a href="#" class="page-link"></a>]
		$config['attributes'] = array('class' => 'page-link');

		$this->pagination->initialize($config);
		// return pagination links
		return $this->pagination->create_links();
	}
}