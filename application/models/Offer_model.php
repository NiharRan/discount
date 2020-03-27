<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Offer_Model extends CI_Model
{
	private $table			= 'offers';			// offer table

	function __construct()
	{
        parent::__construct();
        $this->load->model("restaurant_model");
        $this->load->model("template_model");
        $this->load->model("user_model");
        $this->load->model("global_model");
        $this->load->model("template_model");
    }

    /**
     * this method store new offer
     * @param data object
     * @return bool
     */
    function store($data)
    {
        // store offer info
        $this->db->insert($this->table, $data);
        // return last stored offer id
        return $this->db->insert_id();
    }

    /**
     * this method update offer
     * @param data object
     * @param offer_id integer
     * @return bool
     */
    function update($data, $offer_id)
    {
        return $this->db->where('offer_id', $offer_id)->update($this->table, $data);
    }

    /**
     * this method delete offer
     * @param offer_id ineger
     * @return bool
     */
    function remove($offer_id)
    {
        return $this->db->where('offer_id', $offer_id)->delete($this->table);
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_offer_rows($query)
    {
        $search = $query['search'];
        $restaurant_ids = isset($query['restaurant_ids']) ? $query['restaurant_ids'] : '';

        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('offer_name', $search);
        if(!empty($restaurant_ids)) $this->db->where_in('restaurant_id', $restaurant_ids);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return offers object list
     */
    function fetch_all_offers($limit, $start, $query)
    {
        $search = $query['search'];
        $restaurant_ids = isset($query['restaurant_ids']) ? $query['restaurant_ids'] : '';
        
        $this->db->select('offers.*, templates.template_name')->from($this->table);
        // join template table with offers table
        $this->db->join('templates', 'templates.template_id=offers.template_id', 'left')
                 ->where('templates.template_status', 1);
        // if client search something
        if(!empty($search)) $this->db->like('offer_name', $search);
        if(!empty($restaurant_ids)) $this->db->where_in('restaurant_id', $restaurant_ids);
        $query = $this->db->limit($limit, $start)->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $offer) {
            $query[$key]['restaurant'] = $this->global_model->with('restaurants', 'restaurant_id', $offer['restaurant_id']);
            $query[$key]['user'] = $this->global_model->with('users', 'id', $offer['offer_creator']);
            $query[$key]['template'] = $this->global_model->with('templates', 'template_id', $offer['template_id']);
        }
        return $query;
    }

    /**
     * this method fetch offer info on condition
     * @return offerInfo object 
     */
    public function fetch_offer_on_condition($query)
    {
        $this->db->select('*')->from('offers');
        
        if (isset($query['offer_id'])) {
            $this->db->where('offer_id', $query['offer_id']);
        }

        if (isset($query['offer_creator'])) {
            $this->db->where('offer_creator', $query['offer_creator']);
        }

        if (isset($query['offer_slug'])) {
            $this->db->where('offer_slug', $query['offer_slug']);
        }

        if (isset($query['singe_date'])) {
            $date = $query['single_date'];
            $this->db->where("offer_start <= '$date' AND offer_end >= '$date'");
        }
        $this->db->group_by('offer_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();
        // with restaurant, user, template info
        foreach ($query as $key => $offer) {
            $query[$key]['restaurant'] = $this->global_model->with('restaurants', 'restaurant_id', $offer['restaurant_id']);
            $query[$key]['restaurant']['tags'] = $this->tag_model->fetch_all_tags_of_restaurant($offer['restaurant_id']);
            $query[$key]['user'] = $this->global_model->with('users', 'id', $offer['offer_creator']);
            $query[$key]['template'] = $this->global_model->with('templates', 'template_id', $offer['template_id']);
        }
        return count($query) == 1 ? $query[0] : $query;
    }


    function fetch_all_running_offer_of_restaurant($restaurant_id)
    {
        $this->db->select('*')->from('offers');
        $this->db->where("offers.restaurant_id", $restaurant_id);
        $date = date("Y-m-d");
        $this->db->where("offer_start <= '$date' AND offer_end >= '$date'");
        $query = $this->db->get()->result_array();
        return $query;
    }

    /**
     * this method fetch total rows from offertypes table
     * if offer_type_status === 1
     * @return offertypes object list
     */
    function fetch_all_active_offer_types()
    {
        return $this->db->select('*')
                        ->from('offertypes')
                        ->where('offer_type_status', 1)
                        ->get()
                        ->result_array();
    }

    /**
     * this method update offer status
     * @param offer_status
     * @param offer_id
     * @return true/false
     */
	function change_status($status, $id)
	{
		$status = $status == 1 ? 0 : 1;
		return $this->db->set('offer_status', $status)
						->where('offer_id', $id)
						->update($this->table);
	}
}