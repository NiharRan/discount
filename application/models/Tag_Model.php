<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tag_Model
 *
 * This model store tag data. It operates the following tables:
 * - tag table data
 *
 */
class Tag_Model extends CI_Model
{
	private $table			= 'tags';			// tag table

	function __construct()
	{
		parent::__construct();

    }

    /**
     * this method store new tag
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update tage
     * @param data object
     * @param tag_id integer
     * @return bool
     */
    function update($data, $tag_id)
    {
        return $this->db->where('tag_id', $tag_id)->update($this->table, $data);
    }

    /**
     * this method delete tag
     * @param tag_id ineger
     * @return bool
     */
    function remove($tag_id)
    {
        return $this->db->where('tag_id', $tag_id)->delete($this->table);
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_tag_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('tag_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return tags object list
     */
    function fetch_all_tags($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('tag_name', $search);
        return $this->db->limit($limit, $start)->get();
    }

    /**
     * fetch all active tags
     * @return taglist array
     */
    function fetch_all_active_tags()
    {
        return $this->db->select('*')
                        ->from('tags')
                        ->where('tag_status', 1)
                        ->get();
    }
}