<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Size_Model extends CI_Model
{
	private $table			= 'food_sizes';			// size table

	function __construct()
	{
		parent::__construct();

    }

    /**
     * this method store new size
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update size
     * @param data object
     * @param food_size_id integer
     * @return bool
     */
    function update($data, $food_size_id)
    {
        return $this->db->where('food_size_id', $food_size_id)->update($this->table, $data);
    }

    /**
     * this method delete size
     * @param food_size_id integer
     * @return bool
     */
    function remove($food_size_id)
    {
        return $this->db->where('food_size_id', $food_size_id)->delete($this->table);
    }


     /**
     * this method fetch size info on condition
     * @return sizeInfo object 
     */
    function fetch_food_size_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['food_size_id'])) {
            $this->db->where('food_size_id', $query['food_size_id']);
        }

        if(isset($query['food_size_slug'])) {
            $this->db->where('food_size_slug', $query['food_size_slug']);
        }

        $this->db->group_by('food_size_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $size) {
            
        }
        return count($query) == 1 ? $query[0] : $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_food_size_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('food_size_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return sizes object list
     */
    function fetch_all_sizes($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('food_size_name', $search);
        return $this->db->limit($limit, $start)->get()->result_array();
    }

    /**
     * fetch all active sizes
     * @return sizelist array
     */
    function fetch_all_active_sizes()
    {
        return $this->db->select('*')
                        ->from($this->table)
                        ->where('food_size_status', 1)
                        ->get()
                        ->result_array();
    }
}