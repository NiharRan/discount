<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Restaurant_Model
 *
 * This model store restaurant data. It operates the following tables:
 * - restaurant table data
 *
 */
class Restaurant_Model extends CI_Model
{
	private $table			= 'restaurants';			// restaurant table

	function __construct()
	{
		parent::__construct();

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
     * this method fetch all resturants created by the user
     * @param user_id ineger
     * @return restaurantList array
     */
	function fetch_restaurants_by_user_info($user_id)
	{
		return $this->db->select('*')
						->from('restaurants')
						->where('restaurant_creator', $user_id)
						->get();
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
        if(!empty($search)) $this->db->like('restaurent_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return restaurents object list
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
			$tags = $this->fetch_all_tags_of_restaurant($restaurant['restaurant_id']);
			$query[$key]['tags'] = $tags->result();

			// this metho dfetch all tags of a restaurant
			$ratings = $this->fetch_all_ratings_of_restaurant($restaurant['restaurant_id']);
			$query[$key]['rating'] = '';
			// total rows
			$totalRows = $ratings->num_rows();
			// sum of rating points
			$sumOfRating = 0;
			foreach ($ratings->result() as $rating) {
				$sumOfRating += $rating->rating;
			}
			// if restaurant has rating
			if ($totalRows != 0) {
				// average of rating
				$query[$key]['totalRated'] = $totalRows;
				$query[$key]['rating'] =  number_format($sumOfRating / $totalRows, 1, '.', '');
			}

			// this method fetch restaurant creator info
			$user = $this->fetch_user_info_by_restaurant($restaurant['restaurant_id']);
			$query[$key]['creator'] = $user->row();

		}

		// return data from server
		return $query;
	}

	 /**
     * this method fetch single restaurant
     * @param limit integer
     * @return restaurent object
     */
	function fetch_restaurant_info_by_slug($slug)
	{
		$query = $this->db->select('*')
						  ->from('restaurants')
						  ->where('restaurant_slug', $slug)
						  ->limit(1)
						  ->get()
						  ->result_array();

		foreach ($query as $key => $restaurant) {
			// this metho dfetch all tags of a restaurant
			$tags = $this->fetch_all_tags_of_restaurant($restaurant['restaurant_id']);
			$query[$key]['tags'] = $tags->result();

			// this metho dfetch all tags of a restaurant
			$ratings = $this->fetch_all_ratings_of_restaurant($restaurant['restaurant_id']);
			$query[$key]['rating'] = '';
			// total rows
			$totalRows = $ratings->num_rows();
			// sum of rating points
			$sumOfRating = 0;
			foreach ($ratings->result() as $rating) {
				$sumOfRating += $rating->rating;
			}
			// if restaurant has rating
			if ($totalRows != 0) {
				// average of rating
				$query[$key]['totalRated'] = $totalRows;
				$query[$key]['rating'] =  number_format($sumOfRating / $totalRows, 1, '.', '');
			}

			// this method fetch restaurant creator info
			$user = $this->fetch_user_info_by_restaurant($restaurant['restaurant_id']);
			$query[$key]['creator'] = $user->row();

		}

		// return data from server
		return $query;
	}
	
	/**
	 * this method fetch all tags by restaurant id
	 * @param restaurant_id
	 * @return tags
	 */
	function fetch_all_tags_of_restaurant($restaurant_id)
	{
		return $this->db->select('tags.*')
						->from('tags')
						->join('restaurant_tags', 'tags.tag_id=restaurant_tags.tag_id')
						->where(array(
							'restaurant_tags.restaurant_id' => $restaurant_id,
							'tag_status'    => 1
						))
						->get();
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
	 * this method fetch all user info by restaurant id
	 * @param restaurant_id
	 * @return user
	 */
	function fetch_user_info_by_restaurant($restaurant_id)
	{
		return $this->db->select('users.*')
						->from('users')
						->join('restaurants', 'users.id=restaurants.restaurant_creator')
						->where(array(
							'restaurants.restaurant_id' => $restaurant_id,
						))
						->limit(1)
						->get();
	}

	/**
	 * this method fetch all rating info by restaurant id
	 * @param restaurant_id
	 * @return rating
	 */
	function fetch_all_ratings_of_restaurant($restaurant_id)
	{
		return $this->db->select('ratings.*')
						->from('ratings')
						->join('restaurants', 'ratings.restaurant_id=restaurants.restaurant_id')
						->where(array(
							'restaurants.restaurant_id' => $restaurant_id,
						))
						->get();
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
	 * this method restaurant by restaurant id & creator
	 * @param restaurant_id
	 * @param restaurant_creator
	 * @return restaurant
	 */
	function fetch_restaurant_by_creator_and_id($restaurant_creator, $restaurant_id)
	{
		return $this->db->select('*')
						->from($this->table)
						->where(array(
							'restaurant_id' => $restaurant_id,
							'restaurant_creator' => $restaurant_creator,
						))
						->get();
	}
}