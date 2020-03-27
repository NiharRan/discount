<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


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
     * this method fetch tag info on condition
     * @return tagInfo object 
     */
    function fetch_tag_on_condition($query)
    {
        $this->db->select('*')->from($this->table);

        if(isset($query['tag_id'])) {
            $this->db->where('tag_id', $query['tag_id']);
        }

        if(isset($query['tag_slug'])) {
            $this->db->where('tag_slug', $query['tag_slug']);
        }

        $this->db->group_by('tag_id')->order_by('visit_count', 'desc');
        if(isset($query['limit'])) {
            $this->db->limit($query['limit'], 0);
        }
        $query = $this->db->get()->result_array();

        // with restaurant, user, template info
        foreach ($query as $key => $tag) {
            $query[$key]['restaurants'] = $this->restaurant_model->fetch_all_restaurants_of_tag($tag['tag_id']);
            foreach ($query[$key]['restaurants'] as $k => $restaurant) {
                $query[$key]['restaurants'][$k]['offers'] = $this->offer_model->fetch_all_running_offer_of_restaurant($restaurant['restaurant_id']);
            }
        }
        return count($query) == 1 ? $query[0] : $query;
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
        return $this->db->limit($limit, $start)->get()->result_array();
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
                        ->get()
                        ->result_array();
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
						->get()
						->result_array();
	}
}