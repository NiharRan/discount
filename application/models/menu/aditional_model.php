<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Aditional_Model extends CI_Model
{
	private $table			= 'food_aditionals';			// food_aditional table

	function __construct()
	{
        parent::__construct();
        $this->load->model('global_model');
    }

    /**
     * this method store new food_aditional
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update food_aditional
     * @param data object
     * @param food_aditional_id integer
     * @return bool
     */
    function update($data, $food_aditional_id)
    {
        return $this->db->where('food_aditional_id', $food_aditional_id)->update($this->table, $data);
    }

    /**
     * this method delete food_aditional
     * @param food_aditional_id integer
     * @return bool
     */
    function remove($food_aditional_id)
    {
        return $this->db->where('food_aditional_id', $food_aditional_id)->delete($this->table);
    }


     /**
     * this method fetch food_aditional info on condition
     * @return food_aditionalInfo object 
     */
    function fetch_food_aditional_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['food_aditional_id'])) {
            $this->db->where('food_aditional_id', $query['food_aditional_id']);
        }


        $this->db->group_by('food_aditional_id')->order_by('food_aditional', 'asc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();
        return $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_food_aditional_rows($query)
    {
        $search = $query['search'];
        $this->db->select($this->table.'.*')->from($this->table);
        if(!empty($search)) $this->db->like('food_aditional_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return food_aditionals object list
     */
    function fetch_all_food_aditionals($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select($this->table.'.*')->from($this->table);
        // if client search something
        $query = $this->db->limit($limit, $start)->get()->result_array();
        return $query;
    }

    /**
     * fetch all active food_aditionals
     * @return food_aditionallist array
     */
    function fetch_all_active_food_aditionals()
    {
        return $this->db->select('*')
                        ->from('food_aditionals')
                        ->where('food_aditional_status', 1)
                        ->get()
                        ->result_array();
    }
}