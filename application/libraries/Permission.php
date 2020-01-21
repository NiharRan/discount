<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permission
{
	private $error = array();

	function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->config('tank_auth', TRUE);

		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->model('tank_auth/users');
	}


	/**
	 * Logout user from the site
	 *
	 * @return	void
	 */
	function has_permission($model, $action)
	{
		$query = $this->ci->db->select('*')->from('permissions')->where(array(
			'model_name' => $model,
			'action' => $action,
			'permission_status' => 1,
			'role_id' => $this->ci->session->userdata('user_type')
		))->get();

		return $query->num_rows() > 0;
	}

}
