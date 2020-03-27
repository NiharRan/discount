<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Permission_Model extends CI_Model
{
	private $table			= 'permissions';			// permission table

	function __construct()
	{
        parent::__construct();
        $this->load->model('global_model');
    }

    /**
     * this method store new permission
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update permission
     * @param data object
     * @param permission_id integer
     * @return bool
     */
    function update($data, $permission_id)
    {
        return $this->db->where('permission_id', $permission_id)->update($this->table, $data);
    }

    /**
     * this method delete permission
     * @param permission_id integer
     * @return bool
     */
    function remove($permission_id)
    {
        return $this->db->where('permission_id', $permission_id)->delete($this->table);
    }


     /**
     * this method fetch permission info on condition
     * @return permissionInfo object 
     */
    function fetch_permission_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['permission_id'])) {
            $this->db->where('permission_id', $query['permission_id']);
        }

        $this->db->group_by('permission_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();
        return count($query) == 1 ? $query[0] : $query;
    }

    /**
     * this method fetch permission info on condition
     * @return permissionInfo object 
     */
    function fetch_user_permissions()
    {
        $this->db->select('*')->from($this->table);

        $this->db->where('role_id', $this->session->userdata('role'));
        $query = $this->db->get()->result_array();
        return $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_permission_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) {
            $this->db->like('model_name', $search)
                    ->or_like('action', $search);
        }
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return permissions object list
     */
    function fetch_all_permissions($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) {
            $this->db->like('model_name', $search)
                    ->or_like('action', $search);
        }
        $query = $this->db->limit($limit, $start)->get()->result_array();

        foreach ($query as $key => $permission) {
            $query[$key]['role'] = $this->global_model->has_one('roles', 'role_id', $permission['role_id']);
        }

        return $query;
    }

    /**
     * fetch all active permissions
     * @return permissionList array
     */
    function fetch_all_active_permissions()
    {
        return $this->db->select('*')
                        ->from('permissions')
                        ->where('permission_status', 1)
                        ->get()
                        ->result_array();
    }
}