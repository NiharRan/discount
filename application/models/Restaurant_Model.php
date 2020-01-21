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
}