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
		$this->load->library('custom');
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
			array('field' => 'user_type', 'label' => 'User Type', 'rules' => 'required'),
			array('field' => 'username', 'label' => 'Username', 'rules' => 'required'),
			array('field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]'),
		);
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			$username = $this->input->post("username");
			if (!$this->is_already_exists($username)) {
				// store user info
				$dob = empty($this->input->post('dob')) ? '' : $this->input->post('dob');
				$jsonData['check'] = true;
				$data = array(
					'name'           => $this->input->post('name'),
					'username'       => $username, 
					'contact_number' => $this->input->post('contact_number'),
					'email'          => $this->input->post('email'),
					'user_type'      => $this->input->post('user_type'),
					'city'           => $this->input->post('city'),
					'country'        => $this->input->post('country'),
					'address'        => $this->input->post('address'),
					'dob'            => $dob,
					'postal_code'    => $this->input->post('postal_code'),
					'activated'      => 1,
					'created'        => date('Y-m-d'),
					'password'       => $this->tank_auth->create_password($this->input->post("password"))
				);
				// store user through user model
				$id = $this->user_model->store($data);

				if ($id) {
					// if user banner is provided
					if(isset($_FILES['banner']['name']) && $_FILES['banner']['name'] != ''){
						$folder = 'user-'.$id;
						$ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
						$file_name = 'banner-'.time().'.'.$ext;

						$_FILES['banner']['name']=$file_name;
						$path = './uploads/user/'.$folder;

						// if directory not exists, create
						if (!is_dir($path)) {
							mkdir($path, 0777, true);
						}
						$config['upload_path'] 		= $path;
						$config['allowed_types'] 	='*';
						
						// load upload library
						$this->load->library('upload', $config);
						// check image uploaded or not
						if($this->upload->do_upload('banner')){
							$uploadData = $this->upload->data();
							// resize image
							$query['path'] = $uploadData['full_path'];
							$query['width'] = 1000;
							$query['height'] = 600;
							$this->resizeImage($query);

							// update user info
							$imageData = array(
								'banner' => $file_name,
							);
							$this->db->where('id',$id);
							$this->db->update('users',$imageData);
						}
					}

					// if user avatar is provided
					if(isset($_FILES['avatar']['name']) && $_FILES['avatar']['name'] != ''){
						$folder = 'user-'.$id;
						$ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
						$file_name = 'avatar-'.time().'.'.$ext;

						$_FILES['avatar']['name']=$file_name;
						$path = './uploads/user/'.$folder;

						// if directory not exists, create
						if (!is_dir($path)) {
							mkdir($path, 0777, true);
						}
						$config['upload_path'] 		= $path;
						$config['allowed_types'] 	='*';
						
						// load upload library
						$this->load->library('upload', $config);
						// check image uploaded or not
						if($this->upload->do_upload('avatar')){
							$uploadData = $this->upload->data();
							// resize image
							$query['path'] = $uploadData['full_path'];
							$query['width'] = 200;
							$query['height'] = 115;
							$this->resizeImage($query);

							// update user info
							$imageData = array(
								'avatar' => $file_name,
							);
							$this->db->where('id',$id);
							$this->db->update('users',$imageData);
						}
					}
					$jsonData['success'] = true;
				}
			} else {
				$this->form_validation->set_message("username", "This username is already exists");
			}
			
		} else {
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}
		
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * this method check user has permission to perform user action
	 * @param action
	 * @return	object
	 */
	function has_permission_to_action_user()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$action = $this->input->post('action');
		if ($this->permission->has_permission('user', $action)) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * user info update by this method. 
	 * Return success TRUE if user info updated successfully
	 * otherwise success FALSE.
	 *
	 * @param	
	 * @return	object
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		// create validation rules array
		$rules = array(
			array('field' => 'name', 'label' => 'User Name', 'rules' => 'required'),
			array('field' => 'user_type', 'label' => 'User Type', 'rules' => 'required'),
			array('field' => 'username', 'label' => 'Username', 'rules' => 'required'),
			array('field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]'),
		);
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			$id = $this->input->post("id");
			$banner = $this->input->post('banner');
			$avatar = $this->input->post('avatar');
			$dob = empty($this->input->post('dob')) ? '' : $this->input->post('dob');

			// update user info
			$jsonData['check'] = true;
			$data = array(
				'name'           => $this->input->post('name'),
				'contact_number' => $this->input->post('contact_number'),
				'email'          => $this->input->post('email'),
				'user_type'      => $this->input->post('user_type'),
				'city'           => $this->input->post('city'),
				'country'        => $this->input->post('country'),
				'address'        => $this->input->post('address'),
				'dob'            => $dob,
				'postal_code'    => $this->input->post('postal_code'),
			);
			// update user through user model
			$result = $this->user_model->update($data, $id);

			if ($result) {
				// if user banner is provided
				if(isset($_FILES['new_banner']['name']) && $_FILES['new_banner']['name'] != ''){
					$folder = 'user-'.$id;
					$ext = pathinfo($_FILES['new_banner']['name'], PATHINFO_EXTENSION);
					$banner_name = 'banner-'.time().'.'.$ext;
					$banner_thumb_name = 'avatar-'.time().'_thumb.'.$ext;

					$_FILES['new_banner']['name']=$banner_name;
					$path = './uploads/user/'.$folder;

					// old banner path
					$oldBanner = $path.'/'.$banner;
					$onlyName = substr($banner, 0, strpos($banner, '.'));
					$oldBannerThumb = $path.'/'.$onlyName.'_thumb.'.$ext;
					/**
					 * check if old banner is exists
					 * then delete first
					 */
					if (file_exists($oldBanner)) {
						// give permission to delete
						chmod($oldBanner, 0777);
						unlink($oldBanner);
					}
					if (file_exists($oldBannerThumb)) {
						// give permission to delete
						chmod($oldBannerThumb, 0777);
						unlink($oldBannerThumb);
					}

					// if directory not exists, create
					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}
					$config['upload_path'] 		= $path;
					$config['allowed_types'] 	='*';
					
					// load upload library
					$this->load->library('upload', $config);
					// check image uploaded or not
					if($this->upload->do_upload('new_banner')){
						$uploadData = $this->upload->data();
						// resize image
						$query['path'] = $uploadData['full_path'];
						$query['width'] = 1000;
						$query['height'] = 600;
						$this->resizeImage($query);

						// update user info
						$imageData = array(
							'banner' => $banner_name,
							'banner_thumb' => $banner_thumb_name,
						);
						$this->db->where('id',$id)->update('users',$imageData);
					}
				}

				// if user avatar is provided
				if(isset($_FILES['new_avatar']['name']) && $_FILES['new_avatar']['name'] != ''){
					$folder = 'user-'.$id;
					$ext = pathinfo($_FILES['new_avatar']['name'], PATHINFO_EXTENSION);
					$avatar_name = 'avatar-'.time().'.'.$ext;
					$avatar_thumb_name = 'avatar-'.time().'_thumb.'.$ext;

					$_FILES['new_avatar']['name']=$avatar_name;
					$path = './uploads/user/'.$folder;

					// old Avatar path
					$oldAvatar = $path.'/'.$avatar;
					$onlyName = substr($avatar, 0, strpos($avatar, '.'));
					$oldAvatarThumb = $path.'/'.$onlyName.'_thumb.'.$ext;
					/**
					 * check if old Avatar is exists
					 * then delete first
					 */
					if (file_exists($oldAvatar)) {
						// give permission to delete
						chmod($oldAvatar, 0777);
						unlink($oldAvatar);
					}
					if (file_exists($oldAvatarThumb)) {
						// give permission to delete
						chmod($oldAvatarThumb, 0777);
						unlink($oldAvatarThumb);
					}

					// if directory not exists, create
					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}
					$config['upload_path'] 		= $path;
					$config['allowed_types'] 	='*';
					
					// load upload library
					$this->load->library('upload', $config);
					// check image uploaded or not
					if($this->upload->do_upload('new_avatar')){
						$uploadData = $this->upload->data();
						// resize image
						$query['path'] = $uploadData['full_path'];
						$query['width'] = 200;
						$query['height'] = 115;
						$this->resizeImage($query);

						// update user info
						$imageData = array(
							'avatar' => $avatar_name,
							'avatar_thumb' => $avatar_thumb_name,
						);
						$this->db->where('id',$id)->update('users',$imageData);
					}
				}
				$jsonData['success'] = true;
			}
			
		} else {
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}
		
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * this method resize image 
	 * @param array [path, width, height]
	 * @return boolean
	 */
	function resizeImage($data)
	{
		$cropConfig['image_library'] = 'gd2';
		$cropConfig['source_image'] = $data['path'];
		$cropConfig['create_thumb'] = TRUE;
		$cropConfig['maintain_ratio'] = TRUE;
		$cropConfig['width']         = $data['width'];
		$cropConfig['height']       = $data['height'];

		$this->load->library('image_lib');
		$this->image_lib->initialize($cropConfig);
		$this->image_lib->resize();
		$this->image_lib->clear();
	}

	/**
	 * this method check username is already used or not
	 * from user name
	 * @return string username
	 */
	function is_already_exists($username, $user_id = '')
	{
		$user = $this->user_model->fetch_user_info_on_condition(array('username' => $username));
		if ($user_id == '' && count($user) > 0) return true;
		if($user_id != '' && count($user) > 0 && $user['id'] != $user_id) return true;
		return false;
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
		if (count($usertypes) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $usertypes;
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
		if (count($users )> 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $users;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * user status change by this method. 
	 * if status change
	 * Return success true 
	 * otherwise false.
	 *
	 * @return	true/false
	 */
	function changestatus()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$activated = $this->input->post('activated');
		$id = $this->input->post('id');
		// change status through this method
		$result = $this->user_model->change_status($activated, $id);

		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * user delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param user_id
	 * @return	bool
	 */
	function delete($user_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->user_model->remove($user_id);
		// if user deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}
}