<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Food extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('menu/food_model');
		$this->load->model('global_model');
	}

	/***
	 * @route {{ menu/foods }}
	 * @return food list page
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
			$data['title'] = 'Offer.com || Product List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/menu/food';
			$data['content'] = 'admin/menu/food';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new food store by this method. 
	 * Return TRUE if foods are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	food_name 
	 * @param	category_id 
	 * @param	food_lowest_price 
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('food_name', 'Name',  'required');
		$this->form_validation->set_rules('category_id', 'Category',  'required');
		$this->form_validation->set_rules('food_lowest_price', 'Lowest price',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;
			// get size name and id
			$data['food_name'] = $this->input->post('food_name');
			$data['category_id'] = $this->input->post('category_id');
			$data['food_lowest_price'] = $this->input->post('food_lowest_price');
            $data['food_doc'] = date('Y-m-d H:i:s');
            $data['food_creator'] = $this->tank_auth->get_user_id();
			$food_id = $this->input->post('food_id');

			$food_id = $this->food_model->store($data);

			// if data stored make success true
			if ($food_id) {
				$food_tags = explode(',', $this->input->post('food_tags'));
				if (sizeof($food_tags) > 0) {
					foreach ($food_tags as $menu_tag_id) {
						$data = array(
							'food_id' => $food_id,
							'menu_tag_id' => $menu_tag_id,
						);
						$result = $this->food_model->save_food_tag($data);
					}
				}

				// if food banner is provided
				if(isset($_FILES['food_banner']['name']) && $_FILES['food_banner']['name'] != ''){
					$folder = 'food-'.$food_id;
					$ext = pathinfo($_FILES['food_banner']['name'], PATHINFO_EXTENSION);
					$file_name = 'banner-'.time().'.'.$ext;

					$_FILES['food_banner']['name']=$file_name;
					$path = './uploads/food/'.$folder;

					// if directory not exists, create
					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}
					$config['upload_path'] 		= $path;
					$config['allowed_types'] 	='*';
					
					// load upload library
					$this->load->library('upload', $config);
					// check image uploaded or not
					if($this->upload->do_upload('food_banner')){
						$uploadData = $this->upload->data();
						// resize image
						$query['path'] = $uploadData['full_path'];
						$query['width'] = 1000;
						$query['height'] = 600;
						$this->resizeImage($query);

						// update food info
						$imageData = array(
							'food_banner' => $file_name,
						);
						$this->db->where('food_id',$food_id);
						$this->db->update('foods',$imageData);
					}
				}

				// if food logo is provided
				if(isset($_FILES['food_modal_banner']['name']) && $_FILES['food_modal_banner']['name'] != ''){
					$folder = 'food-'.$food_id;
					$ext = pathinfo($_FILES['food_modal_banner']['name'], PATHINFO_EXTENSION);
					$file_name = 'logo-'.time().'.'.$ext;

					$_FILES['food_modal_banner']['name']=$file_name;
					$path = './uploads/food/'.$folder;

					// if directory not exists, create
					if (!is_dir($path)) {
						mkdir($path, 0777, true);
					}
					$config['upload_path'] 		= $path;
					$config['allowed_types'] 	='*';
					
					// load upload library
					$this->load->library('upload', $config);
					// check image uploaded or not
					if($this->upload->do_upload('food_modal_banner')){
						$uploadData = $this->upload->data();
						// resize image
						$query['path'] = $uploadData['full_path'];
						$query['width'] = 200;
						$query['height'] = 115;
						$this->resizeImage($query);

						// update food info
						$imageData = array(
							'food_modal_banner' => $file_name,
						);
						$this->db->where('food_id',$food_id);
						$this->db->update('foods',$imageData);
					}
				}
				$jsonData['success'] = true;
			}
		}else {
			// return form_validation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
        }
        
        // send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * size info update by this method. 
	 * Return TRUE if size are updated successfully
	 * otherwise FALSE.
	 * @param	food_name 
	 * @param	category_id 
	 * @param	food_lowest_price 
	 * @param   food_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('food_name', 'Name',  'required');
		$this->form_validation->set_rules('category_id', 'Category',  'required');
		$this->form_validation->set_rules('food_lowest_price', 'Lowest price',  'required');
		if ($this->form_validation->run()) {
			// if form validation successful
			$jsonData['check'] = true;

			// get food id
			$food_id = $this->input->post('food_id');
			$food_banner = $this->input->post('food_banner');
			$food_modal_banner = $this->input->post('food_modal_banner');

			// if food banner is provided
			if(isset($_FILES['food_new_banner']['name']) && $_FILES['food_new_banner']['name'] != ''){
				$folder = 'food-'.$food_id;
				$ext = pathinfo($_FILES['food_new_banner']['name'], PATHINFO_EXTENSION);
				$file_name = 'banner-'.time().'.'.$ext;

				$_FILES['food_new_banner']['name']=$file_name;
				$path = './uploads/food/'.$folder;

				// old banner path
				$oldBanner = $path.'/'.$food_banner;
				$onlyName = substr($food_banner, 0, strpos($food_banner, '.'));
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
				if($this->upload->do_upload('food_new_banner')){
					$uploadData = $this->upload->data();
					// resize image
					$query['path'] = $uploadData['full_path'];
					$query['width'] = 1000;
					$query['height'] = 600;
					$this->resizeImage($query);
					// update food info
					$imageData = array(
						'food_banner' => $file_name,
					);
					$this->db->where('food_id',$food_id);
					$this->db->update('foods',$imageData);
				}
			}

			// if food modal_banner is provided
			if(isset($_FILES['food_new_modal_banner']['name']) && $_FILES['food_new_modal_banner']['name'] != ''){
				$folder = 'food-'.$food_id;
				$ext = pathinfo($_FILES['food_new_modal_banner']['name'], PATHINFO_EXTENSION);
				$file_name = 'modal_banner-'.time().'.'.$ext;

				$_FILES['food_new_modal_banner']['name']=$file_name;
				$path = './uploads/food/'.$folder;

				// old modal_banner path
				$oldModalBanner = $path.'/'.$food_modal_banner;
				$onlyName = substr($food_modal_banner, 0, strpos($food_modal_banner, '.'));
				$oldModalBannerThumb = $path.'/'.$onlyName.'_thumb.'.$ext;
				/**
				 * check if old modal_banner is exists
				 * then delete first
				 */
				if (file_exists($oldModalBanner)) {
					// give permission to delete
					chmod($oldModalBanner, 0777);
					unlink($oldModalBanner);
				}
				if (file_exists($oldModalBannerThumb)) {
					// give permission to delete
					chmod($oldModalBannerThumb, 0777);
					unlink($oldModalBannerThumb);
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
				if($this->upload->do_upload('food_new_modal_banner')){
					$uploadData = $this->upload->data();
					// resize image
					$query['path'] = $uploadData['full_path'];
					$query['width'] = 200;
					$query['height'] = 115;
					$this->resizeImage($query);
					// update food info
					$imageData = array(
						'food_modal_banner' => $file_name,
					);
					$this->db->where('food_id',$food_id);
					$this->db->update('foods',$imageData);
				}
			}

			// get size name and id
			$data['food_name'] = $this->input->post('food_name');
			$data['category_id'] = $this->input->post('category_id');
			$data['food_lowest_price'] = $this->input->post('food_lowest_price');
			$data['food_dom'] = date('Y-m-d H:i:s');
			$food_id = $this->input->post('food_id');

			$result = $this->food_model->update($data, $food_id);
			// if data updated make success true
			if ($result) {
				$food_tags = explode(',', $this->input->post('food_tags'));
				if (sizeof($food_tags) > 0) {
					$this->db->where('food_id', $food_id)->delete('food_tags');
					foreach ($food_tags as $menu_tag_id) {
						$data = array(
							'food_id' => $food_id,
							'menu_tag_id' => $menu_tag_id,
						);
						$result = $this->food_model->save_food_tag($data);
					}
				}
				$jsonData['success'] = true;
			}
		}else {
			// return form_validation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = strip_tags(form_error($key));
			}
		}

		// send response to clint
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
		$this->image_lib->clear();
		$this->image_lib->initialize($cropConfig);
		$this->image_lib->resize();
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
	 * size delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param food_id
	 * @return	bool
	 */
	function delete($food_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->food_model->remove($food_id);
		// if size deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all foods by this method. 
	 * Return size list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] size list
	 */
	function allfoods()
	{
		// take the last size id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 size from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in foods table according to search query
		$total_rows = $this->food_model->fetch_total_food_rows($query);
		// fetch 10 foods start from 'offset' where query
		$foods = $this->food_model->fetch_all_foods($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'menu/food-foods/all/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if foods is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => size list
		 * 	 links => pagination links
		 */
		if (count($foods) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $foods;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active foods by this method. 
	 * Return size list if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] size list
	 */
	function allActivefoods()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$foods = $this->food_model->fetch_all_active_foods();
		/**
		 * if foods is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => size list
		 */
		if (count($foods) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $foods;
		}
		// response send
		echo json_encode($jsonData);
	}

	/**
	 * size status change by this method. 
	 * if status change
	 * Return success true 
	 * otherwise false.
	 *
	 * @return	true/false
	 */
	function changeStatus()
	{
		// initialize response data
		$jsonData = array('success' => false);
		$food_status = $this->input->post('food_status');
		$food_id = $this->input->post('food_id');
		// change status through this method
		$result = $this->db->set('food_status', $food_status == 1 ? 0 : 1)
						->where('food_id', $food_id)
						->update("foods");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * one record fetch by this method. 
	 * @param food_id.
	 * @return	food_info
	 */
	function single()
	{
		// initialize response data
		$query['food_id'] = $this->uri->segment(3);
		$result = $this->food_model->fetch_food_on_condition($query);
		// if status changed 
		if ($result) {
			// send response to client
			echo json_encode($result[0]);
		}
	}
    
}
