<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Order_Model extends CI_Model
{
	private $table			= 'orders';			// orders table

	function __construct()
	{
        parent::__construct();
        $this->load->model('global_model');
    }

    /**
     * this method store new order
     * @param data object
     * @return bool
     */
    function store($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

     /**
     * this method store new customer
     * @param data object
     * @return bool
     */
    function store_customer_info($data)
    {
        $this->db->insert('customers', $data);
        return $this->db->insert_id();
    }

    /**
     * this method store new food order info
     * @param data object
     * @return bool
     */
    function store_order_item_info($data)
    {
        $this->db->insert('order_foods', $data);
        return $this->db->insert_id();
    }

    /**
     * this method update order
     * @param data object
     * @param order_id integer
     * @return bool
     */
    function update($data, $order_id)
    {
        return $this->db->where('order_id', $order_id)->update($this->table, $data);
    }

    /**
     * this method delete order
     * @param order_id integer
     * @return bool
     */
    function remove($order_id)
    {
        return $this->db->where('order_id', $order_id)->delete($this->table);
    }


     /**
     * this method fetch order info on condition
     * @return orderInfo object 
     */
    function fetch_order_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['order_id'])) {
            $this->db->where('order_id', $query['order_id']);
        }

        if(isset($query['restaurant_id'])) {
            $this->db->where('restaurant_id', $query['restaurant_id']);
        }

        $this->db->group_by('order_id')->order_by('order_id', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $order) {
            $food_prices = $this->global_model->belong_to_many('order_foods', 'food_prices', 'order_id', $order['order_id'], 'food_price_id');
            foreach ($food_prices as $ke => $food_price) {
                $food_prices[$ke]['food'] = $this->global_model->has_one('foods', 'food_id', $food_price['food_id']);
                $food_prices[$ke]['food_size'] = $this->global_model->has_one('food_sizes', 'food_size_id', $food_price['food_size_id']);
                $order_food = $this->db->select('*')
                    ->from('order_foods')
                    ->where(array(
                        'order_id' => $order['order_id'],
                        'food_price_id' => $food_price['food_price_id']
                    ))
                    ->get()
                    ->row_array();
                $food_prices[$ke]['aditional_price'] = $order_food['food_aditional_price'];

                $food_prices[$ke]['food_aditionals'] = $this->db->select('*')
                    ->from('food_aditionals')
                    ->where_in('food_aditional_id', explode(',', $order_food['food_aditional_id']))
                    ->get()
                    ->result_array();
            }
            $query[$key]['food_prices'] = $food_prices;
            $query[$key]['customer'] = $this->global_model->has_one('customers', 'customer_id', $order['customer_id']);
            $query[$key]['restaurant'] = $this->global_model->has_one('restaurants', 'restaurant_id', $order['restaurant_id']);
        }
        return $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_order_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('order_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return orders object list
     */
    function fetch_all_orders($limit, $start, $query)
    {
        $this->db->select('*')->from($this->table);
        $query = $this->db->limit($limit, $start)->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $order) {
            $food_prices = $this->global_model->belong_to_many('order_foods', 'food_prices', 'order_id', $order['order_id'], 'food_price_id');
            foreach ($food_prices as $ke => $food_price) {
                $food_prices[$ke]['food'] = $this->global_model->has_one('foods', 'food_id', $food_price['food_id']);
                $food_prices[$ke]['food_size'] = $this->global_model->has_one('food_sizes', 'food_size_id', $food_price['food_size_id']);
                $order_food = $this->db->select('*')
                    ->from('order_foods')
                    ->where(array(
                        'order_id' => $order['order_id'],
                        'food_price_id' => $food_price['food_price_id']
                    ))
                    ->get()
                    ->row_array();
                $food_prices[$ke]['aditional_price'] = $order_food['food_aditional_price'];

                $food_prices[$ke]['food_aditionals'] = $this->db->select('*')
                    ->from('food_aditionals')
                    ->where_in('food_aditional_id', explode(',', $order_food['food_aditional_id']))
                    ->get()
                    ->result_array();
            }
            $query[$key]['food_prices'] = $food_prices;
            $query[$key]['customer'] = $this->global_model->has_one('customers', 'customer_id', $order['customer_id']);
            $query[$key]['restaurant'] = $this->global_model->has_one('restaurants', 'restaurant_id', $order['restaurant_id']);
        }
        return $query;
    }

    /**
     * fetch all active orders
     * @return orderlist array
     */
    function fetch_all_active_orders()
    {
        return $this->db->select('*')
                        ->from('orders')
                        ->where('order_status', 1)
                        ->get()
                        ->result_array();
    }
}