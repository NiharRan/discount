
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
                        ->row_array();
        return $query;
	}

	/**
	 * this method fetch object list by primary key
	 * @param table, primary_key, value
	 * @return object
	 */
	function has_many($table, $primary_key, $value)
	{
		$query = $this->db->select('*')
						->from($table)
						->where(array(
							$primary_key => $value,
						))
						->get()
                        ->result_array();
        return $query;
	}

	/**
	* this method fetch multiple by primary key
	* @param join_table 
	* @param target_table
	* @param searching_key
	* @return searching_value
	* @return join_key
			
	* @return object_list
	*/
	function belong_to_many($join_table, $target_table, $searching_key, $searching_value, $join_key)
	{
		$query = $this->db->select($target_table.'.*')
						->from($target_table)
						->join($join_table, $join_table.'.'.$join_key.'='.$target_table.'.'.$join_key, 'left')
						->where(array(
							$searching_key => $searching_value,
						))
						->get()
                        ->result_array();
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
