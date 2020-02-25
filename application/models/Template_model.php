<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template_Model
 *
 * This model store template data. It operates the following tables:
 * - template table data
 *
 */
class Template_Model extends CI_Model
{
	private $table			= 'templates';			// template table

	function __construct()
	{
		parent::__construct();
        $this->load->model("user_model");
    }

    /**
     * this method store new template
     * @param data object
     * @return bool
     */
    function store($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * this method update templatee
     * @param data object
     * @param template_id integer
     * @return bool
     */
    function update($data, $template_id)
    {
        return $this->db->where('template_id', $template_id)->update($this->table, $data);
    }

    /**
     * this method delete template
     * @param template_id ineger
     * @return bool
     */
    function remove($template_id)
    {
        return $this->db->where('template_id', $template_id)->delete($this->table);
    }

    /**
     * this method fetch total number of rows
     * @param query object
     * @return total_rows integer
     */
    function fetch_total_template_rows($query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        if(!empty($search)) $this->db->like('template_name', $search);
        return $this->db->get()->num_rows();
    }

     /**
     * this method fetch total rows
     * @param limit integer
     * @param start integer
     * @param query object
     * @return templates object list
     */
    function fetch_all_templates($limit, $start, $query)
    {
        $search = $query['search'];
        $this->db->select('*')->from($this->table);
        // if client search something
        if(!empty($search)) $this->db->like('template_name', $search);
        $query = $this->db->limit($limit, $start)->get()->result_array();

        //  with template creator info
        foreach ($query as $key => $template) {
            $query[$key]['user'] = $this->user_model->fetch_user_info_by_id($template['template_creator']);
        }
        return $query;
    }

    /**
     * fetch all active templates
     * @return templatelist array
     */
    function fetch_all_active_templates()
    {
        $query = $this->db->select('*')
                        ->from('templates')
                        ->where('template_status', 1)
                        ->get()
                        ->result_array();

        //  with template creator info
        foreach ($query as $key => $template) {
            $query[$key]['user'] = $this->user_model->fetch_user_info_by_id($template['template_creator']);
        }
        return $query;
    }

    /**
     * this method fetch template info
     * @param id integer
     * @return templateInfo object 
     */
    function fetch_template_info_by_id($template_id)
    {
        return $this->db->select('*')
                        ->from('templates')
                        ->where('template_id', $template_id)
                        ->get()
                        ->row_array();
    }
}