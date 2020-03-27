<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Category_Model extends CI_Model
{
	private $table			= 'categories';			// category table

	function __construct()
	{
		parent::__construct();

    }

    /**
     * this method store new category
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update category
     * @param data object
     * @param category_id integer
     * @return bool
     */
    function update($data, $category_id)
    {
        return $this->db->where('category_id', $category_id)->update($this->table, $data);
    }

    /**
     * this method delete category
     * @param category_id integer
     * @return bool
     */
    function remove($category_id)
    {
        return $this->db->where('category_id', $category_id)->delete($this->table);
    }


     /**
     * this method fetch category info on condition
     * @return categoryInfo object 
     */
    function fetch_category_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['category_id'])) {
            $this->db->where('category_id', $query['category_id']);
        }

        if(isset($query['category_slug'])) {
            $this->db->where('category_slug', $query['category_slug']);
        }

        $this->db->group_by('category_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $category) {
            
        }
        return count($query) == 1 ? $query[0] : $query;
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_category_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('category_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return categories object list
     */
    function fetch_all_categories($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('category_name', $search);
        return $this->db->limit($limit, $start)->get()->result_array();
    }

    /**
     * fetch all active categories
     * @return categorylist array
     */
    function fetch_all_active_categories()
    {
        return $this->db->select('*')
                        ->from('categories')
                        ->where('category_status', 1)
                        ->get()
                        ->result_array();
    }
}