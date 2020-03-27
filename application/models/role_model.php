<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_Model extends CI_Model
{
	private $table			= 'roles';			// role table

	function __construct()
	{
		parent::__construct();
        $this->load->model("user_model");
    }

    /**
     * this method store new role
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update rolee
     * @param data object
     * @param role_id integer
     * @return bool
     */
    function update($data, $role_id)
    {
        return $this->db->where('role_id', $role_id)->update($this->table, $data);
    }

    /**
     * this method delete role
     * @param role_id ineger
     * @return bool
     */
    function remove($role_id)
    {
        return $this->db->where('role_id', $role_id)->delete($this->table);
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_role_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('role_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return roles object list
     */
    function fetch_all_roles($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('role_name', $search);
        $query = $this->db->limit($limit, $start)->get()->result_array();

        //  with role creator info
        foreach ($query as $key => $role) {
            $query[$key]['user'] = $this->user_model->fetch_user_info_by_id($role['role_creator']);
        }
        return $query;
    }

    /**
     * fetch all active roles
     * @return rolelist array
     */
    function fetch_all_active_roles()
    {
        $query = $this->db->select('*')
                        ->from('roles')
                        ->where('role_status', 1)
                        ->get()
                        ->result_array();

        //  with role creator info
        foreach ($query as $key => $role) {
            $query[$key]['user'] = $this->user_model->fetch_user_info_by_id($role['role_creator']);
        }
        return $query;
    }

    /**
     * this method fetch role info
     * @param id integer
     * @return roleInfo object 
     */
    function fetch_role_info_by_id($role_id)
    {
        return $this->db->select('*')
                        ->from('roles')
                        ->where('role_id', $role_id)
                        ->get()
                        ->row_array();
    }
}