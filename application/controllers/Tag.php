<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('tag_model');
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
			$data['title'] = 'Offer.com || Tag List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/tag';
			$data['content'] = 'admin/settings/tag';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new tag store by this method. 
	 * Return TRUE if tags are stored successfully
	 * otherwise FALSE.
	 *
	 * @param	stringArray	(tag_names [single or multiple])
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false);
		// convert tags from string to array
		$tags = explode(',', $_POST['tags']);

		foreach ($tags as $key => $value) {
			// store all tags one by one
			$data = array(
				'tag_name' => $tags[$key],
				'tag_slug' => url_title($tags[$key]),
				'tag_status' => 1,
				'tag_created_at' => date('Y-m-d')
			);
			// store tag through tag model
			$result = $this->tag_model->store($data);
			// if stored successfully return true
			if($result) $jsonData['success'] = true;
			else $jsonData['success'] = false;
		}
		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * tag info update by this method. 
	 * Return TRUE if tag are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	tag_name
	 * @param tag_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('tag_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successfull
			$jsonData['check'] = true;
			// get tag name and id
			$data['tag_name'] = $this->input->post('tag_name');
			$data['tag_slug'] = url_title($this->input->post('tag_name'));
			$tag_id = $this->input->post('tag_id');

			$result = $this->tag_model->update($data, $tag_id);

			// if data updated make success true
			if ($result) {
				$jsonData['success'] = true;
			}
		}else {
			// return formvalidation errors
			foreach ($_POST as $key => $value) {
				$jsonData['errors'][$key] = form_error($key);
			}
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * tag delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param tag_id
	 * @return	bool
	 */
	function delete($tag_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->tag_model->remove($tag_id);
		// if tag deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all tags by this method. 
	 * Return taglist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] taglist
	 */
	function alltags()
	{
		// take the last tag id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 tag from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in tags table according to search query
		$total_rows = $this->tag_model->fetch_total_tag_rows($query);
		// fetch 10 tags start from 'offset' where query
		$tags = $this->tag_model->fetch_all_tags($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'tag/alltags/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if tags is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => taglist
		 * 	 links => pagination links
		 */
		if (count($tags) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $tags;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active tags by this method. 
	 * Return taglist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] taglist
	 */
	function allactivetags()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$tags = $this->tag_model->fetch_all_active_tags();
		/**
		 * if tags is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => taglist
		 */
		if (count($tags) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $tags;
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * tag status change by this method. 
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
		$tag_status = $this->input->post('tag_status');
		$tag_id = $this->input->post('tag_id');
		// change status through this method
		$result = $this->db->set('tag_status', $tag_status == 1 ? 0 : 1)
						->where('tag_id', $tag_id)
						->update("tags");
		// if status changed 
		if ($result) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}
    
}
