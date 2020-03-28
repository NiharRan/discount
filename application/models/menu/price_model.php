<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Price_Model extends CI_Model
{
	private $table			= 'food_prices';			// food_price table

	function __construct()
	{
        parent::__construct();
        $this->load->model('global_model');
    }

    /**
     * this method store new food_price
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update food_price
     * @param data object
     * @param food_price_id integer
     * @return bool
     */
    function update($data, $food_price_id)
    {
        return $this->db->where('food_price_id', $food_price_id)->update($this->table, $data);
    }

    /**
     * this method delete food_price
     * @param food_price_id integer
     * @return bool
     */
    function remove($food_price_id)
    {
        return $this->db->where('food_price_id', $food_price_id)->delete($this->table);
    }


     /**
     * this method fetch food_price info on condition
     * @return food_priceInfo object 
     */
    function fetch_food_price_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['food_price_id'])) {
            $this->db->where('food_price_id', $query['food_price_id']);
        }


        $this->db->group_by('food_price_id')->order_by('food_price', 'asc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with food && food_size info
        foreach ($query as $key => $food_price) {
            $query[$key]['food'] = $this->global_model->has_one('foods', 'food_id', $food_price['food_id']);
            $query[$key]['food_size'] = $this->global_model->has_one('food_sizes', 'food_size_id', $food_price['food_size_id']);
        }
        return $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_food_price_rows($query)
    {
        $search = $query['search'];
        $this->db->select($this->table.'.*')->from($this->table);
        $this->db->join('foods', 'foods.food_id='.$this->table.'.food_id', 'left');
        if(!empty($search)) $this->db->like('food_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return food_prices object list
     */
    function fetch_all_food_prices($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select($this->table.'.*')->from($this->table);
        // if client search something
        $this->db->join('foods', 'foods.food_id='.$this->table.'.food_id', 'left');
        $query = $this->db->limit($limit, $start)->get()->result_array();

        foreach ($query as $key => $food_price) {
            $query[$key]['food'] = $this->global_model->has_one('foods', 'food_id', $food_price['food_id']);
            $query[$key]['food_size'] = $this->global_model->has_one('food_sizes', 'food_size_id', $food_price['food_size_id']);
        }
        return $query;
    }

    /**
     * fetch all active food_prices
     * @return food_pricelist array
     */
    function fetch_all_active_food_prices()
    {
        return $this->db->select('*')
                        ->from('food_prices')
                        ->where('food_price_status', 1)
                        ->get()
                        ->result_array();
    }
}