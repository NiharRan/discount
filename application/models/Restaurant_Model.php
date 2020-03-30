<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Restaurant_Model extends CI_Model
{
	private $table			= 'restaurants';			// restaurant table

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('tag_model');
		$this->load->model('rating_model');
		$this->load->model('template_model');
		$this->load->model('offer_model');
		$this->load->model('global_model');
	}

	/**
     * this method store new restaurant
     * @param data object
     * @return integer restaurant_id
     */
    function store($data)
    {
		$this->db->insert($this->table, $data);
		// return last inserted restaurant id
		return $this->db->insert_id();
	}

	/**
     * this method update restaurant
     * @param data
     * @param restaurnt_id
     * @return true/false
     */
    function update($data, $restaurant_id)
    {
		return $this->db->where('restaurant_id', $restaurant_id)
						->update($this->table, $data);
	}
	
	/**
	 * this method store restaurant tags
	 * @param data object
	 * @return boolean
	 */
	function add_tags_with_restaurant($data)
	{
		return $this->db->insert('restaurant_tags', $data);
	}
	
	/**
     * this method fetch all restaurants created by the user
     * @param user_id integer
     * @return restaurantList array
     */
	function fetch_restaurants_by_user_info($user_id)
	{
		return $this->db->select('*')
						->from('restaurants')
						->where('restaurant_creator', $user_id)
						->get()
						->result_array();
	}

	/**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_restaurant_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) {
			$this->db->like('restaurant_name', $search);
		}
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return restaurants object list
     */
    function fetch_all_restaurants($limit, $start, $query)
    {
        $search = $query['search'];
        $restaurant_creator = $query['restaurant_creator'];
		$this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('restaurant_name', $search);
        if(!empty($restaurant_creator)) $this->db->where('restaurant_creator', $restaurant_creator);
		$query = $this->db->limit($limit, $start)->get()->result_array();
		
		foreach ($query as $key => $restaurant) {
			// this metho dfetch all tags of a restaurant
			$tags = $this->tag_model->fetch_all_tags_of_restaurant($restaurant['restaurant_id']);
            $query[$key]['tags'] = $this->global_model->belong_to_many('restaurant_tags', 'tags', 'restaurant_id', $restaurant['restaurant_id'], 'tag_id');

			// this metho dfetch all tags of a restaurant
			$categories = $this->global_model->has_many('categories', 'restaurant_id', $restaurant['restaurant_id']);
			$query[$key]['categories'] = $categories;

			// this method fetch all tags of a restaurant
			$ratings = $this->global_model->with('ratings', 'restaurant_id', $restaurant['restaurant_id']);
			$query[$key]['rating'] = '';
			// total rows
			$totalRows = count($ratings);
			// sum of rating points
			$sumOfRating = 0;
			foreach ($ratings as $rating) {
				$sumOfRating += $rating['rating'];
			}
			// if restaurant has rating
			if ($totalRows != 0) {
				// average of rating
				$query[$key]['totalRated'] = $totalRows;
				$query[$key]['rating'] =  number_format($sumOfRating / $totalRows, 1, '.', '');
			}

			// this method fetch restaurant creator info
			$user = $this->global_model->with('users', 'id', $restaurant['restaurant_creator']);
			$query[$key]['creator'] = $user;

		}

		// return data from server
		return $query;
	}

	/**
	 * this method remove many to many relation 
	 * between tag & restaurant by restaurant id
	 * @param restaurant_id
	 * @return true/false
	 */
	function remove_restaurant_tags($restaurant_id)
	{
		return $this->db->where('restaurant_id', $restaurant_id)
						->delete('restaurant_tags');
	}


	/**
     * this method update restaurant status
     * @param restaurant_status
     * @param restaurant_id
     * @return true/false
     */
	function change_status($status, $id)
	{
		$status = $status == 1 ? 0 : 1;
		return $this->db->set('restaurant_status', $status)
						->where('restaurant_id', $id)
						->update($this->table);
	}

	/**
     * this method update restaurant status
     * @param restaurant_status
     * @param restaurant_id
     * @return true/false
     */
	function change_feature_status($status, $id)
	{
		$status = $status == 1 ? 0 : 1;
		return $this->db->set('feature_restaurant', $status)
						->where('restaurant_id', $id)
						->update($this->table);
	}

	 /**
     * this method fetch restaurant info on condition
     * @return restaurantInfo object 
     */
    public function fetch_restaurant_on_condition($query)
    {
        $this->db->select('*')->from('restaurants');
        
        if (isset($query['restaurant_id']) && !empty($query['restaurant_id'])) {
            $this->db->where('restaurant_id', $query['restaurant_id']);
        }

        if (isset($query['restaurant_creator']) && !empty($query['restaurant_creator'])) {
            $this->db->where('restaurant_creator', $query['restaurant_creator']);
		}
		
		if (isset($query['restaurant_slug']) && !empty($query['restaurant_slug'])) {
            $this->db->where('restaurant_slug', $query['restaurant_slug']);
        }

        $query = $this->db->get()->result_array();
        // with restaurant info
        foreach ($query as $key => $restaurant) {
            // this metho dfetch all tags of a restaurant
			$tags = $this->tag_model->fetch_all_tags_of_restaurant($restaurant['restaurant_id']);
            $query[$key]['tags'] = $this->global_model->belong_to_many('restaurant_tags', 'tags', 'restaurant_id', $restaurant['restaurant_id'], 'tag_id');

			// this metho dfetch all tags of a restaurant
			$categories = $this->global_model->has_many('categories', 'restaurant_id', $restaurant['restaurant_id']);
			$query[$key]['categories'] = $categories;

			// this metho dfetch all tags of a restaurant
			$ratings = $this->global_model->with('ratings', 'restaurant_id', $restaurant['restaurant_id']);
			$query[$key]['rating'] = '';
			// total rows
			$totalRows = count($ratings);
			// sum of rating points
			$sumOfRating = 0;
			foreach ($ratings as $rating) {
				$sumOfRating += $rating['rating'];
			}
			// if restaurant has rating
			if ($totalRows != 0) {
				// average of rating
				$query[$key]['totalRated'] = $totalRows;
				$query[$key]['rating'] =  number_format($sumOfRating / $totalRows, 1, '.', '');
			}

			// this method fetch restaurant creator info
			$user = $this->global_model->with('users', 'id', $restaurant['restaurant_creator']);
			$query[$key]['creator'] = $user;
        }
        return count($query) == 1 ? $query[0] : $query;
    }

	/**
	 * this method fetch restaurants by creator
	 * @param restaurant_creator
	 * @return restaurant
	 */
	function fetch_all_active_restaurants_of_user($restaurant_creator)
	{
		return $this->db->select('*')
						->from($this->table)
						->where(array(
							'restaurant_status' => 1,
							'restaurant_creator' => $restaurant_creator,
						))
						->get()
						->result_array();
	}

	/**
	 * this method fetch restaurants by creator
	 * @param restaurant_creator
	 * @return restaurant
	 */
	function fetch_all_restaurants_of_user($restaurant_creator)
	{
		return $this->db->select('*')
						->from($this->table)
						->where(array(
							'restaurant_creator' => $restaurant_creator,
						))
						->get()
						->result_array();
	}


	/**
	 * this method fetch restaurants by searching
	 * @param restaurant_creator
	 * @return restaurant
	 */
	function fetch_some_restaurants($restaurant_name)
	{
		return $this->db->select('*')
						->from($this->table)
						->like('restaurant_name', $restaurant_name)
						->limit(5)
						->get()
						->result_array();
	}


	/**
	 * this method fetch restaurants by tag
	 * @param tag_id
	 * @return restaurant
	 */
	function fetch_all_restaurants_of_tag($tag_id)
	{
		return $this->db->select('restaurants.*')
						->from('restaurants')
						->join('restaurant_tags', 'restaurants.restaurant_id=restaurant_tags.restaurant_id')
						->where(array(
							'restaurant_tags.tag_id' => $tag_id,
							'restaurant_status'    => 1
						))
						->get()
						->result_array();
	}


	function fetch_restaurants_by_key($key)
	{
		return $this->db->select('restaurants.*')
						->from('restaurants')
						->where("restaurant_name LIKE '$key%'")
						->get()
						->result_array();
	}

	function count_restaurants_by_key($key)
	{
		return $this->db->select('restaurants.*')
						->from('restaurants')
						->where("restaurant_name LIKE '$key%'")
						->get()
						->num_rows();
	}

}