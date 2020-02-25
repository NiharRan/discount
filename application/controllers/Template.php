<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('permission');
		$this->load->library('custom');
		$this->load->model('template_model');
		$this->user_id = $this->tank_auth->get_user_id();
	}

	/***
	 * @route {{ settings/templates }}
	 * @return template list page
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
			$data['title'] = 'Offer.com || Template List';
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			
			// view page data
			$data['extrastyle'] = 'inc/_vuestyle';
			$data['extrascript'] = 'inc/_vuescript';
			$data['vuecomponent'] = 'components/template';
			$data['content'] = 'admin/settings/template';
			$this->load->view('layouts/master', $data);
		}
	}
	
	/**
	 * new template store by this method. 
	 * Return TRUE if template is stored successfully
	 * otherwise FALSE.
	 *
	 * @param	array	(template_name, template_body)
	 * @return	bool
	 */
	function store()
	{
		// response array
		$jsonData = array('success' => false);
		
		// store all templates one by one
		$data = array(
			'template_name' => $this->input->post('template_name'),
			'template_status' => 1,
			'template_created_at' => date('Y-m-d'),
			'template_creator' => $this->user_id,
		);
		// store template through template model
		$result = $this->template_model->store($data);
		// if stored successfully return true
		if($result) $jsonData['success'] = true;

		// send the response to client
		echo json_encode($jsonData);
	}

	/**
	 * template info update by this method. 
	 * Return TRUE if template are updated successfully
	 * otherwise FALSE.
	 *
	 * @param	template_name
	 * @param template_id
	 * @return	bool
	 */
	function update()
	{
		// response array
		$jsonData = array('success' => false, 'check' => false, 'errors' => array());
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('template_name', 'Name',  'required');
		if ($this->form_validation->run()) {
			// if form validation successfull
			$jsonData['check'] = true;
			$data = array(
				'template_name' => $this->input->post('template_name'),
				'template_creator' => $this->user_id,
			);
			$template_id = $this->input->post('template_id');

			$result = $this->template_model->update($data, $template_id);

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
	 * template delete by this method. 
	 * Return TRUE if deleted successfully
	 * otherwise FALSE.
	 *
	 * @param template_id
	 * @return	bool
	 */
	function delete($template_id)
	{
		// response array
		$jsonData = array('success' => false);
		$result = $this->template_model->remove($template_id);
		// if template deleted successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * this method check user has permission to perform template action
	 * @param template_id
	 * @param template_creator
	 * @return	object
	 */
	function has_permission_to_action_template()
	{
		// intialize response data
		$jsonData = array('success' => false);
		$template_creator = $this->input->post('template_creator');
		$template_id = $this->input->post('template_id');
		$action = $this->input->post('action');
		if ($this->permission->has_permission('template', $action)) {
			$jsonData['success'] = true;
		}
		// send response to client
		echo json_encode($jsonData);
	}

	/**
	 * template status change by this method. 
	 * Return TRUE if status changed successfully
	 * otherwise FALSE.
	 *
	 * @param template_id
	 * @return	bool
	 */
	function changeStatus()
	{
		$template_status = $this->input->post('template_status');
		$template_id = $this->input->post('template_id');
		// response array
		$jsonData = array('success' => false);
		$result = $this->db->set('template_status', $template_status == 1 ? 0 : 1)
						->where('template_id', $template_id)
						->update("templates");
		// if template status changed successfully
		if($result) {
			$jsonData['success'] = true;
		}

		// send response to clint
		echo json_encode($jsonData);
	}

	/**
	 * fetch all templates by this method. 
	 * Return templatelist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] templatelist
	 */
	function alltemplates()
	{
		// take the last template id
		$offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
		// fetch 10 template from server
		$perpage = 10;
		// take search parameters
		$query['search'] = $this->input->get('search');

		// response object
		$jsonData = array('success' => false, 'data' => array(), 'links' => '');

		// total rows in templates table according to search query
		$total_rows = $this->template_model->fetch_total_template_rows($query);
		// fetch 10 templates start from 'offset' where query
		$templates = $this->template_model->fetch_all_templates($perpage, $offset, $query);
		// config data to create pagination
		$obj = array(
			'base_url' => base_url().'template/alltemplates/',
			'per_page' => $perpage,
			'uri_segment' => 2,
			'total_rows' => $total_rows
		);
		/**
		 * if templates is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => templatelist
		 * 	 links => pagination links
		 */
		if (count($templates) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $templates;
			$jsonData['links'] = $this->custom->paginate($obj);
		}
		// responde send
		echo json_encode($jsonData);
	}

	/**
	 * fetch all active templates by this method. 
	 * Return templatelist if table is not empty
	 * otherwise null.
	 *
	 * @return	array[object] templatelist
	 */
	function allactivetemplates()
	{
		// response object
		$jsonData = array('success' => false, 'data' => array());

		$templates = $this->template_model->fetch_all_active_templates();
		/**
		 * if templates is not empty
		 * @response object
		 * 	 success => everything all right
		 *   data => templatelist
		 */
		if (count($templates) > 0) {
			$jsonData['success'] = true;
			$jsonData['data'] = $templates;
		}
		// responde send
		echo json_encode($jsonData);
	}
    
}
