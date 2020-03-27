
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Global_Model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('tag_model');
		$this->load->model('rating_model');
		$this->load->model('template_model');
		$this->load->model('offer_model');
	}
	

    /**
	 * this method fetch only object by primary key
	 * @param table, primary_key, value
	 * @return object
	 */
	function with($table, $primary_key, $value)
	{
		$query = $this->db->select('*')
						->from($table)
						->where(array(
							$primary_key => $value,
						))
						->get()
                        ->result_array();
        return count($query) == 1 ? $query[0] : $query;
	}

	/**
	 * this method fetch only object by primary key
	 * @param table, primary_key, value
	 * @return object
	 */
	function has_one($table, $primary_key, $value)
	{
		$query = $this->db->select('*')
						->from($table)
						->where(array(
							$primary_key => $value,
						))
						->get()
                        ->row();
        return $query;
	}

	/**
	 * this method fetch total rows
	 * @param table,
	 * @return object
	 */
	public function total_rows($table)
	{
		return $this->db->select('*')
						->from($table)
						->get()
						->num_rows();
	}
}
