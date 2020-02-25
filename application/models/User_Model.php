<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User_Model
 *
 * This model store user data. It operates the following tables:
 * - user table data
 *
 */
class User_Model extends CI_Model
{
	private $table			= 'users';			// user table

	function __construct()
	{
        parent::__construct();
        $this->load->model("restaurant_model");
    }

    /**
     * this method store new user
     * @param data object
     * @return bool
     */
    function store($data)
    {
        // store user info
        $this->db->insert($this->table, $data);
        // return last stored user id
        return $this->db->insert_id();
    }

    /**
     * this method update user
     * @param data object
     * @param user_id integer
     * @return bool
     */
    function update($data, $user_id)
    {
        return $this->db->where('id', $user_id)->update($this->table, $data);
    }

    /**
     * this method delete user
     * @param user_id ineger
     * @return bool
     */
    function remove($user_id)
    {
        return $this->db->join("restaurants", "restaurants.restaurant_creator=users.id", "left")
                        ->join("offers", "offers.offer_creator=users.id", "left")
                        ->join("templates", "templates.template_creator=users.id", "left")
                        ->join("tags", "tags.tag_creator=users.id", "left")
                        ->where('id', $user_id)->delete($this->table);
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_user_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('user_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return users object list
     */
    function fetch_all_users($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('users.*, usertypes.user_type_name')->from($this->table);
        // join usertypes table with users table
        $this->db->join('usertypes', 'usertypes.user_type_id=users.user_type', 'left')
                 ->where('usertypes.user_type_status', 1);
        // if client search something
        if(!empty($search)) $this->db->like('user_name', $search);
        return $this->db->limit($limit, $start)->get()->result_array();
    }

    /**
     * this method fetch user info on condition
     * @return userInfo object 
     */
    public function fetch_user_info_on_condition($query)
    {
        $this->db->select('*')->from('users');
        
        if (isset($query['id'])) {
            $this->db->where('id', $query['id']);
        }

        if (isset($query['username'])) {
            $this->db->where('username', $query['username']);
        }

        $query = $this->db->get()->result_array();
        // with restaurant info
        foreach ($query as $key => $user) {
            $query[$key]['restaurant'] = $this->restaurant_model->fetch_all_restaurants_of_user($user['id']);
        }
        return count($query) == 1 ? $query[0] : $query;
    }

    /**
     * this method fetch user info
     * @param id integer
     * @return userInfo object 
     */
    function fetch_user_info_by_id($id)
    {
        return $this->db->select('*')
                        ->from('users')
                        ->where('id', $id)
                        ->get()
                        ->row_array();
    }

    /**
     * this method fetch total rows from usertypes table
     * if user_type_status === 1
     * @return usertypes object list
     */
    function fetch_all_active_user_types()
    {
        return $this->db->select('*')
                        ->from('usertypes')
                        ->where('user_type_status', 1)
                        ->get()
                        ->result_array();
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
						->get()
						->row_array();
    }
    
    /**
     * this method update user status
     * @param activated
     * @param id
     * @return true/false
     */
	function change_status($status, $id)
	{
		$status = $status == 1 ? 0 : 1;
		return $this->db->set('activated', $status)
						->where('id', $id)
						->update($this->table);
	}
}