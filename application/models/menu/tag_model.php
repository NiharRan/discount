<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Tag_Model extends CI_Model
{
	private $table			= 'menu_tags';			// menu_tag table

	function __construct()
	{
		parent::__construct();

    }

    /**
     * this method store new menu_tag
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update menu_tag
     * @param data object
     * @param menu_tag_id integer
     * @return bool
     */
    function update($data, $menu_tag_id)
    {
        return $this->db->where('menu_tag_id', $menu_tag_id)->update($this->table, $data);
    }

    /**
     * this method delete menu_tag
     * @param menu_tag_id integer
     * @return bool
     */
    function remove($menu_tag_id)
    {
        return $this->db->where('menu_tag_id', $menu_tag_id)->delete($this->table);
    }


     /**
     * this method fetch menu_tag info on condition
     * @return menu_tagInfo object 
     */
    function fetch_menu_tag_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['menu_tag_id'])) {
            $this->db->where('menu_tag_id', $query['menu_tag_id']);
        }

        if(isset($query['menu_tag_slug'])) {
            $this->db->where('menu_tag_slug', $query['menu_tag_slug']);
        }

        $this->db->group_by('menu_tag_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $menu_tag) {
            
        }
        return count($query) == 1 ? $query[0] : $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_menu_tag_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('menu_tag_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return menu_menu_tags object list
     */
    function fetch_all_menu_tags($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('menu_tag_name', $search);
        return $this->db->limit($limit, $start)->get()->result_array();
    }

    /**
     * fetch all active menu_menu_tags
     * @return menu_taglist array
     */
    function fetch_all_active_menu_tags()
    {
        return $this->db->select('*')
                        ->from('menu_menu_tags')
                        ->where('menu_tag_status', 1)
                        ->get()
                        ->result_array();
    }
}