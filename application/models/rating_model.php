<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Rating_Model extends CI_Model
{
	private $table			= 'ratings';			// rating table

	function __construct()
	{
		parent::__construct();

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
						->get()
						->result_array();
	}
}