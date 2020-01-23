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
		$this->load->library('custom');
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
			redirect($_SERVER['HTTP_REFERER']);
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

	/**
	 * new restaurant store by this method. 
	 * Return success TRUE if restaurant info stored successfully
	 * otherwise success FALSE.
	 *
	 * @param	
	 * @return	object
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		// create validation rules array
		$rules = array(
			array('field' => 'restaurant_name', 'label' => 'Name', 'rules' => 'required'),
			array('field' => 'restaurant_address', 'label' => 'Address', 'rules' => 'required'),
			array('field' => 'restaurant_open_at', 'label' => 'Opening time', 'rules' => 'required'),
			array('field' => 'restaurant_close_at', 'label' => 'Closing time', 'rules' => 'required'),
			array('field' => 'restaurant_establish_date', 'label' => 'Establish year', 'rules' => 'required'),
		);
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			$jsonData['check'] = true;
			// store restaurant info
			$restaurantData = array(
				'restaurant_moto' => $this->input->post('restaurant_moto'),
				'restaurant_name' => $this->input->post('restaurant_name'),
				'restaurant_address' => $this->input->post('restaurant_address'),
				'restaurant_open_at' => date('H:i:s', strtotime($this->input->post('restaurant_open_at'))),
				'restaurant_close_at' => date('H:i:s', strtotime($this->input->post('restaurant_close_at'))),
				'restaurant_establish_date' => date('Y-m-d', strtotime($this->input->post('restaurant_establish_date'))),
				'restaurant_moto' => $this->input->post('restaurant_moto'),
				'restaurant_email' => $this->input->post('restaurant_email'),
				'restaurant_slug' => url_title($this->input->post('restaurant_name'), "dash", true),
				'restaurant_created_at' => date('Y-m-d H:i:s'),
				'restaurant_creator' => $this->session->userdata('user_id')
			);
			// store restaurant through restaurant model
			$restaurant_id = $this->restaurant_model->store($restaurantData);

			if ($restaurant_id) {
				$jsonData['success'] = true;

				// if tag selected
				if (!empty($_POST['tags'])) {
					// convert tags from string to array
					$tags = explode(',', $_POST['tags']);
					/**
					 * this method create many to many relationship
					 * with restaurant and tag
					 */
					$this->add_tags_with_restaurant($tags, $restaurant_id);
				}

				// if restaurant banner is provided
				if(isset($_FILES['restaurant_banner']['name']) && $_FILES['restaurant_banner']['name'] != ''){
					$folder = 'restaurant-'.$restaurant_id;
					$ext = pathinfo($_FILES['restaurant_banner']['name'], PATHINFO_EXTENSION);
					$file_name = 'banner-'.time().'.'.$ext;

					$_FILES['restaurant_banner']['name']=$file_name;
					$path = './uploads/restaurant/'.$folder;

					// if directory not exists, create
					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}
					$config['upload_path'] 		= $path;
					$config['allowed_types'] 	='*';
					
					// load upload library
					$this->load->library('upload', $config);
					// check image uploaded or not
					if($this->upload->do_upload('restaurant_banner')){
						// update restaurant info
						$imageData = array(
							'restaurant_banner' => $file_name,
						);
						$this->db->where('restaurant_id',$restaurant_id);
						$this->db->update('restaurants',$imageData);
					}
				}

				// if restaurant logo is provided
				if(isset($_FILES['restaurant_logo']['name']) && $_FILES['restaurant_logo']['name'] != ''){
					$folder = 'restaurant-'.$restaurant_id;
					$ext = pathinfo($_FILES['restaurant_logo']['name'], PATHINFO_EXTENSION);
					$file_name = 'logo-'.time().'.'.$ext;

					$_FILES['restaurant_logo']['name']=$file_name;
					$path = './uploads/restaurant/'.$folder;

					// if directory not exists, create
					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}
					$config['upload_path'] 		= $path;
					$config['allowed_types'] 	='*';
					
					// load upload library
					$this->load->library('upload', $config);
					// check image uploaded or not
					if($this->upload->do_upload('restaurant_logo')){
						// update restaurant info
						$imageData = array(
							'restaurant_logo' => $file_name,
						);
						$this->db->where('restaurant_id',$restaurant_id);
						$this->db->update('restaurants',$imageData);
					}
				}
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
	 * restaurant update form is load by this method 
	 *
	 * @param	restaurant_slug	
	 * @return	object
	 */
    function edit()
	{
		$data['title'] = 'Offer.com || Restaurant Edit Form';
		$data['user_id']	= $this->tank_auth->get_user_id();
		$data['username']	= $this->tank_auth->get_username();
		
		// view page data
		$data['extrastyle'] = 'inc/_vuestyle';
		$data['extrascript'] = 'inc/_vuescript';
		$data['vuecomponent'] = 'components/restaurant/edit';

		$data['content'] = 'admin/restaurant/edit';
		$this->load->view('layouts/master', $data);
	}

	/**
	 * this method fetch restaurant info 
	 * using restaurant slug
	 * @return restaurant info
	 */
	function fetch_by_slug()
	{
		// intialize response data
		$jsonData = array('success' => false, 'data' => '');

		// get restaurant slug from url
		$slug = $this->uri->segment(3);
		// fetch restaurant info
		$restaurant = $this->restaurant_model->fetch_restaurant_info_by_slug($slug);

		// if restaurant info found
		if (count($restaurant) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $restaurant;
		}
		// send response data to client
		echo json_encode($jsonData);
	}

	/**
	 * this method check user has permission to edit restaurant
	 * @param restaurant_id
	 * @param restaurant_creator
	 * @return	object
	 */
	function has_permission_to_edit_restaurant()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$restaurant_creator = $this->input->post('restaurant_creator');
		$restaurant_id = $this->input->post('restaurant_id');
		$action = $this->input->post('action');
		// fetch restaurant through this method
		$data = $this->restaurant_model->fetch_restaurant_by_creator_and_id($restaurant_creator, $restaurant_id);
		// if data found 
		if ($data->num_rows() > 0) {
			if ($this->permission->has_permission('restaurant', $action)) {
				$jsonData['success'] = true;
			}
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * restaurant info update by this method. 
	 * Return success TRUE if restaurant info updated successfully
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
			array('field' => 'restaurant_name', 'label' => 'Name', 'rules' => 'required'),
			array('field' => 'restaurant_address', 'label' => 'Address', 'rules' => 'required'),
			array('field' => 'restaurant_open_at', 'label' => 'Opening time', 'rules' => 'required'),
			array('field' => 'restaurant_close_at', 'label' => 'Closing time', 'rules' => 'required'),
			array('field' => 'restaurant_establish_date', 'label' => 'Establish year', 'rules' => 'required'),
		);
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			$jsonData['check'] = true;

			// get restaurant id
			$restaurant_id = $this->input->post('restaurant_id');
			$restaurant_banner = $this->input->post('restaurant_banner');
			$restaurant_logo = $this->input->post('restaurant_logo');


			// if tag selected
			if (!empty($_POST['tags'])) {
				// convert tags from string to array
				$tags = explode(',', $_POST['tags']);
				/**
				 * first remove old tags
				 * then create many to many relationship
				 * with restaurant and tag
				 */
				$result = $this->restaurant_model->remove_restaurant_tags($restaurant_id);
				if ($result) {
					$this->add_tags_with_restaurant($tags, $restaurant_id);
				}
			}

			// if restaurant banner is provided
			if(isset($_FILES['restaurant_new_banner']['name']) && $_FILES['restaurant_new_banner']['name'] != ''){
				$folder = 'restaurant-'.$restaurant_id;
				$ext = pathinfo($_FILES['restaurant_new_banner']['name'], PATHINFO_EXTENSION);
				$file_name = 'banner-'.time().'.'.$ext;

				$_FILES['restaurant_new_banner']['name']=$file_name;
				$path = './uploads/restaurant/'.$folder;

				// old banner path
				$oldBanner = $path.'/'.$restaurant_banner;
				/**
				 * check if old banner is exists
				 * then delete first
				 */
				if (file_exists($oldBanner)) {
					// give permission to delete
					chmod($oldBanner, 0777);
					unlink($oldBanner);
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
				if($this->upload->do_upload('restaurant_new_banner')){
					// update restaurant info
					$imageData = array(
						'restaurant_banner' => $file_name,
					);
					$this->db->where('restaurant_id',$restaurant_id);
					$this->db->update('restaurants',$imageData);
				}
			}

			// if restaurant logo is provided
			if(isset($_FILES['restaurant_new_logo']['name']) && $_FILES['restaurant_new_logo']['name'] != ''){
				$folder = 'restaurant-'.$restaurant_id;
				$ext = pathinfo($_FILES['restaurant_new_logo']['name'], PATHINFO_EXTENSION);
				$file_name = 'logo-'.time().'.'.$ext;

				$_FILES['restaurant_new_logo']['name']=$file_name;
				$path = './uploads/restaurant/'.$folder;

				// old logo path
				$oldLogo = $path.'/'.$restaurant_logo;
				/**
				 * check if old logo is exists
				 * then delete first
				 */
				if (file_exists($oldLogo)) {
					// give permission to delete
					chmod($oldLogo, 0777);
					unlink($oldLogo);
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
				if($this->upload->do_upload('restaurant_new_logo')){
					// update restaurant info
					$imageData = array(
						'restaurant_logo' => $file_name,
					);
					$this->db->where('restaurant_id',$restaurant_id);
					$this->db->update('restaurants',$imageData);
				}
			}

			// update restaurant info
			$restaurantData = array(
				'restaurant_moto' => $this->input->post('restaurant_moto'),
				'restaurant_name' => $this->input->post('restaurant_name'),
				'restaurant_address' => $this->input->post('restaurant_address'),
				'restaurant_open_at' => date('H:i:s', strtotime($this->input->post('restaurant_open_at'))),
				'restaurant_close_at' => date('H:i:s', strtotime($this->input->post('restaurant_close_at'))),
				'restaurant_establish_date' => date('Y-m-d', strtotime($this->input->post('restaurant_establish_date'))),
				'restaurant_moto' => $this->input->post('restaurant_moto'),
				'restaurant_email' => $this->input->post('restaurant_email'),
				'restaurant_slug' => url_title($this->input->post('restaurant_name'), "dash", true),
				'restaurant_created_at' => date('Y-m-d H:i:s'),
				'restaurant_creator' => $this->session->userdata('user_id')
			);
			// update restaurant through restaurant model
			$result = $this->restaurant_model->update($restaurantData, $restaurant_id);

			if ($result) {
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
	 * this is a helper function
	 * to resize image 
	 */
	function resize($width, $height,$path)
	{
		/* Get original image x y*/
		list($w, $h) = getimagesize($path);
		/* calculate new image size with ratio */
		$ratio = max($width/$w, $height/$h);
		$h = ceil($height / $ratio);
		$x = ($w - $width / $ratio) / 2;
		$w = ceil($width / $ratio);

		$imgString = file_get_contents($path);
		/* create image from string */
		$image = imagecreatefromstring($imgString);
		$tmp = imagecreatetruecolor($width, $height);
		imagecopyresampled($tmp, $image,
		0, 0,
		$x, 0,
		$width, $height,
		$w, $h);
		/* Save image */
		switch ($_FILES['user_avatar']['type']) 
		{
		case 'image/jpeg':
			imagejpeg($tmp, $path, 100);
			break;
		case 'image/png':
			imagepng($tmp, $path, 0);
			break;
		case 'image/gif':
			imagegif($tmp, $path);
			break;
		default:
			exit;
			break;
		}
		return $path;
		/* cleanup memory */
		imagedestroy($image);
		imagedestroy($tmp);
	}

	/**
	 * this method store all tags in restaurant_tags table
	 * to create relationship between restaurants and tags
	 */
	function add_tags_with_restaurant($tags, $restaurant_id)
	{
		foreach ($tags as $key => $value) {
			// store all tags one by one
			$restaurantTagData = array(
				'restaurant_id' => $restaurant_id,
				'tag_id' => $tags[$key],
			);
			// store tag through tag model
			$this->restaurant_model->add_tags_with_restaurant($restaurantTagData);
		}
	}

	/**
	 * fetch all restaurants by this method. 
	 * Return restaurantlist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] restaurantlist
	 */
	function allrestaurants()
	{
		// take the last restaurant id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 restaurant from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');
		$query['restaurant_creator'] = $this->session->userdata('user_id');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in restaurants table according to search query
		$total_rows = $this->restaurant_model->fetch_total_restaurant_rows($query);
		// fetch 10 restaurants start from 'offset' where query
		$restaurants = $this->restaurant_model->fetch_all_restaurants($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'restaurant/allrestaurants/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if restaurants is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => restaurantlist
		 * 	 links => pagination links
		 */
		if (count($restaurants) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $restaurants;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * restaurant status change by this method. 
	 * if status change
	 * Return success true 
	 * otherwise false.
	 *
	 * @return	array[object] restaurantlist
	 */
	function changestatus()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$restaurant_status = $this->input->post('restaurant_status');
		$restaurant_id = $this->input->post('restaurant_id');
		// change status through this method
		$result = $this->restaurant_model->change_status($restaurant_status, $restaurant_id);

		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}
}
