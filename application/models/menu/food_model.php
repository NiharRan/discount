<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Food_Model extends CI_Model
{
	private $table			= 'foods';			// food table

	function __construct()
	{
        parent::__construct();
        $this->load->model('global_model');
    }

    /**
     * this method store new food
     * @param data object
     * @return bool
     */
    function store($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * this method update food
     * @param data object
     * @param food_id integer
     * @return bool
     */
    function update($data, $food_id)
    {
        return $this->db->where('food_id', $food_id)->update($this->table, $data);
    }

    /**
     * this method delete food
     * @param food_id integer
     * @return bool
     */
    function remove($food_id)
    {
        return $this->db->where('food_id', $food_id)->delete($this->table);
    }


     /**
     * this method fetch food info on condition
     * @return foodInfo object 
     */
    function fetch_food_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['food_id'])) {
            $this->db->where('food_id', $query['food_id']);
        }

        if(isset($query['food_slug'])) {
            $this->db->where('food_slug', $query['food_slug']);
        }

        $this->db->group_by('food_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with category info
        foreach ($query as $key => $food) {
            $query[$key]['category'] = $this->global_model->has_one('categories', 'category_id', $food['category_id']);
        }
        return $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_food_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('food_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return foods object list
     */
    function fetch_all_foods($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('food_name', $search);
        $query = $this->db->limit($limit, $start)->get()->result_array();

        foreach ($query as $key => $food) {
            /**
            * this method fetch total rows
            * @param join_table 
            * @param target_table
            * @param searching_key
            * @return searching_value
            * @return join_key
            */
            $query[$key]['food_tags'] = $this->global_model->belong_to_many('food_tags', 'menu_tags', 'food_id', $food['food_id'], 'menu_tag_id');
            $query[$key]['category'] = $this->global_model->has_one('categories', 'category_id', $food['category_id']);
        }
        return $query;
    }

    /**
     * fetch all active foods
     * @return foodlist array
     */
    function fetch_all_active_foods()
    {
        return $this->db->select('*')
                        ->from('foods')
                        ->where('food_status', 1)
                        ->get()
                        ->result_array();
    }


    public function save_food_tag($data)
    {
        $this->db->insert('food_tags', $data);
    }
}