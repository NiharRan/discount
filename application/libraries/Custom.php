<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Custom
{
	private $error = array();

	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('pagination');
		$this->ci->load->library('session');
    }
    

    /**
     * this method create pagination links 
     * according to object parameter
     * @param base_url
     * @param total_rows
     * @param per_page
     * @param uri_segment
     * 
     * @return links
     */
    function paginate($obj)
    {
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_link'] = '<i class="fas fa-angle-left"></i>';
		$config['first_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_link'] = '<i class="fas fa-angle-double-right"></i>';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_link'] = '<i class="fas fa-angle-double-left"></i>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_link'] = '<i class="fas fa-angle-right"></i>';
		$config['last_tag_close'] = '</li>';
		
        $config['cur_tag_open'] = '<li class="active page-item"><span><b>';
		$config['cur_tag_close'] = '</b></span></li>';
		
		// set these mostly...
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'offset';
		$config['reuse_query_string'] = TRUE;


		// custom config
		$config['base_url'] = $obj['base_url'];
		$config['total_rows'] = $obj['total_rows'];
		$config['per_page'] = $obj['per_page'];
		$config['uri_segment'] = $obj['uri_segment'];

		// link tag class [ <a href="#" class="page-link"></a>]
		$config['attributes'] = array('class' => 'page-link');

		$this->ci->pagination->initialize($config);
		// return pagination links
		return $this->ci->pagination->create_links();
    }
    
}