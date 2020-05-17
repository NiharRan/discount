<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends CI_Controller {

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
		$this->load->model('offer_model');
		$this->user_id = $this->tank_auth->get_user_id();
		$this->username = $this->tank_auth->get_username();
    }

    /***
	 * @route {{ offers }}
	 * @return offer list page
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
			$data['title'] = 'Offer.com || Offers';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
            
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';

			// editor cdn src
			$data['editor'] = 'plugins/ckeditor5/ckeditor'; 
			$data['editorVue'] = 'plugins/ckeditor5/ckeditor.min'; 

			$data['vuecomponent'] = 'components/offer/list';
			$data['content'] = 'admin/offer/list';
			$this->load->view('layouts/master', $data);
		}
    }

    	/**
	 * new offer info store by this method. 
	 * if offer are stored successfully
	 * 	Return JSON object with success status true
	 * otherwise errors and success status false.
	 *
	 * @param	stringArray	
	 * @return	object
	 */
    function create()
	{
		if (!$this->permission->has_permission('offer', 'create')) {
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$data['title'] = 'Offer.com || Create New offer';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/offer/create';
			
			// editor cdn src
			$data['editor'] = 'plugins/tinymce/tinymce.min'; 

			$data['content'] = 'admin/offer/create';
			$this->load->view('layouts/master', $data);
		}
	}

	/**
	 * new offer store by this method. 
	 * Return TRUE if offer info stored successfully
	 * otherwise FALSE.
	 *
	 * @param	stringArray	{{name, offer_description, email, offer_type}}
	 * @return	object
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		// create validation rules array
		$rules = array(
			array('field' => 'offer_name', 'label' => 'Offer name', 'rules' => 'required'),
			array('field' => 'offer_description', 'label' => 'Description', 'rules' => 'required'),
			array('field' => 'offer_discount', 'label' => 'Discount', 'rules' => 'required'),
			array('field' => 'offer_start', 'label' => 'Offer start date', 'rules' => 'required'),
			array('field' => 'offer_end', 'label' => 'Offer end date', 'rules' => 'required'),
			array('field' => 'template_id', 'label' => 'Template', 'rules' => 'required'),
		);
		
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			// check offer image is required
			if (isset($_FILES['offer_image']) && $_FILES['offer_image']['name'] != '') {
				$jsonData['check'] = true;
				// store offer info
				$offer_name = $this->input->post('offer_name');
				$restaurant_name = $this->input->post('restaurant_name');
				$data = array(
					'offer_name' => $offer_name,
					'offer_slug' => url_title($this->input->post('offer_name'), "dash", true),
					'offer_description' => $this->input->post('offer_description'),
					'offer_discount' => $this->input->post('offer_discount'),
					'offer_start' => date('Y-m-d', strtotime($this->input->post('offer_start'))),
					'offer_end' => date("Y-m-d", strtotime($this->input->post('offer_end'))),
					'template_id' => $this->input->post('template_id'),
					'restaurant_id' => $this->input->post('restaurant_id'),
					'offer_barcode' => $this->create_barcode($offer_name, $restaurant_name),
					'offer_status' => 1,
					'offer_created_at' => date('Y-m-d H:i:s'),
					'offer_creator' => $this->user_id
				);
				// store offer through offer model
				$offer_id = $this->offer_model->store($data);

				if ($offer_id) {
					// if offer image is provided
					if(isset($_FILES['offer_image']['name']) && $_FILES['offer_image']['name'] != ''){
						$folder = 'offer-'.$offer_id;
						$ext = pathinfo($_FILES['offer_image']['name'], PATHINFO_EXTENSION);
						$time = time();
						$file_name = 'image-'.$time.'.'.$ext;
						$thumb_file_name = 'image-'.$time.'_thumb.'.$ext;

						$_FILES['offer_image']['name']=$file_name;
						$path = './uploads/offer/'.$folder;

						// if directory not exists, create
						if (!is_dir($path)) {
							mkdir($path, 0777, true);
						}
						$config['upload_path'] 		= $path;
						$config['allowed_types'] 	='*';
						
						// load upload library
						$this->load->library('upload', $config);
						// check image uploaded or not
						if($this->upload->do_upload('offer_image')){
							$uploadData = $this->upload->data();
							// resize image
							$query['path'] = $uploadData['full_path'];
							$query['width'] = 1000;
							$query['height'] = 600;
							$this->resizeImage($query);

							// update offer info
							$imageData = array(
								'offer_image' => $file_name,
								'offer_image_thumb' => $thumb_file_name,
							);
							$this->db->where('offer_id',$offer_id);
							$this->db->update('offers',$imageData);
							$jsonData['success'] = true;
						}
					}
				}
			} else {
				$jsonData['errors']['offer_image'] = 'Offer image is required';
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
		$this->image_lib->clear();
		$this->image_lib->initialize($cropConfig);
		$this->image_lib->resize();
	}
	

	/**
	 * offer update by this method. 
	 * Return TRUE if offer info updated successfully
	 * otherwise FALSE.
	 *
	 * @return	object
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		// create validation rules array
		$rules = array(
			array('field' => 'offer_name', 'label' => 'Offer name', 'rules' => 'required'),
			array('field' => 'offer_description', 'label' => 'Description', 'rules' => 'required'),
			array('field' => 'offer_discount', 'label' => 'Discount', 'rules' => 'required'),
			array('field' => 'offer_start', 'label' => 'Offer start date', 'rules' => 'required'),
			array('field' => 'offer_end', 'label' => 'Offer end date', 'rules' => 'required'),
			array('field' => 'template_id', 'label' => 'Template', 'rules' => 'required'),
			array('field' => 'restaurant_id', 'label' => 'Restaurant', 'rules' => 'required'),
		);
		// set rules for validation
		$this->form_validation->set_rules($rules);
		// if validation is done & everything is valid
		if ($this->form_validation->run()) {
			$jsonData['check'] = true;
			// update offer info
			$offer_id = $this->input->post('offer_id');
			$offer_name = $this->input->post('offer_name');
			$offer_image = $this->input->post('offer_image');
			$data = array(
				'offer_name'        => $offer_name,
				'offer_slug'        => url_title($this->input->post('offer_name'), "dash", true),
				'offer_description' => $this->input->post('offer_description'),
				'offer_discount'    => $this->input->post('offer_discount'),
				'offer_start'       => date('Y-m-d', strtotime($this->input->post('offer_start'))),
				'offer_end'         => date("Y-m-d", strtotime($this->input->post('offer_end'))),
				'template_id'       => $this->input->post('template_id'),
				'restaurant_id'     => $this->input->post('restaurant_id'),
			);
			// update offer through offer model
			$result = $this->offer_model->update($data, $offer_id);

			if ($result) {
				// if offer image is provided
				if(isset($_FILES['offer_new_image']['name']) && $_FILES['offer_new_image']['name'] != ''){
					$folder = 'offer-'.$offer_id;
					$ext = pathinfo($_FILES['offer_new_image']['name'], PATHINFO_EXTENSION);
					$time = time();
					$file_name = 'image-'.$time.'.'.$ext;
					$thumb_file_name = 'image-'.$time.'_thumb.'.$ext;

					$_FILES['offer_new_image']['name']=$file_name;
					$path = './uploads/offer/'.$folder;

					// old image path
					$oldImage = $path.'/'.$offer_image;
					$onlyName = substr($offer_image, 0, strpos($offer_image, '.'));
					$oldImageThumb = $path.'/'.$onlyName.'_thumb.'.$ext;
					/**
					 * check if old image is exists
					 * then delete first
					 */
					if (file_exists($oldImage)) {
						// give permission to delete
						chmod($oldImage, 0777);
						unlink($oldImage);
					}
					if (file_exists($oldImageThumb)) {
						// give permission to delete
						chmod($oldImageThumb, 0777);
						unlink($oldImageThumb);
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
					if($this->upload->do_upload('offer_new_image')){
						$uploadData = $this->upload->data();
						// resize image
						$query['path'] = $uploadData['full_path'];
						$query['width'] = 1000;
						$query['height'] = 600;
						$this->resizeImage($query);
						// update offer info
						$imageData = array(
							'offer_image' => $file_name,
							'offer_image_thumb' => $thumb_file_name,
						);
						$this->db->where('offer_id',$offer_id);
						$this->db->update('offers',$imageData);
						$jsonData['success'] = true;
					}
				}else {
					$jsonData['success'] = true;
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
	 * this method generate barcode for offer
	 * @param offer_name
	 * @return barcode
	 */
	function create_barcode($offer_name, $restaurant_name)
	{
		// using first latter of offer_name & current_time
		$barcode = 'OFF'.$restaurant_name[0].$offer_name[0].time();
		return $barcode;
	}




	/**
	 * offer status change by this method. 
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
		$offer_status = $this->input->post('offer_status');
		$offer_id = $this->input->post('offer_id');
		// change status through this method
		$result = $this->offer_model->change_status($offer_status, $offer_id);

		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}




	/**
	 * fetch all offers by this method. 
	 * Return offerlist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] offerlist
	 */
	function alloffers()
	{
		// take the last offer id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 offer from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');
		$query['from'] = 'admin';
		$query['offer_creator'] = $this->session->userdata('user_id');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in offers table according to search query
		$total_rows = $this->offer_model->fetch_total_offer_rows($query);
		// fetch 10 offers start from 'offset' where query
		$offers = $this->offer_model->fetch_all_offers($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'offer/alloffers/',
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
			$jsonData['success'] = true;
			$jsonData['data'] = $offers;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * this method check user has permission to perform offer action
	 * @param offer_id
	 * @param offer_creator
	 * @return	object
	 */
	function has_permission_to_action_offer()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$action = $this->input->post('action');

		// fetch offer through this method
		$query['offer_id'] = $this->input->post('offer_id');
		$query['offer_creator'] = $this->input->post('offer_creator');
		$data = $this->offer_model->fetch_offer_on_condition($query);

		// if data found 
		if (count($data) > 0 && $query['offer_creator'] == $this->user_id) {
			if ($this->permission->has_permission('offer', $action)) {
				$jsonData['success'] = true;
			}
		}
		// send response to client
		echo json_encode($jsonData);
	}
}


