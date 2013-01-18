<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'action.php';

class File extends Action {


	function __construct()
	{
		parent::__construct();
		
        $this->load->helper('file');
        $this->load->helper('image');
	}
	
    /* 스마트 에디터용 파일 목록 */
    function files4Se(){
        
        $imagePath = 'smarteditor/popup/upload/';
        $filenames = get_filenames($imagePath);
        $filenames = array_reverse($filenames,TRUE);
        
        $data['success'] = false;
        if (gettype($filenames)=="array"){
            $data = array();
            foreach ($filenames as $f) {
                $prop = get_image_properties($imagePath.$f,TRUE);
                $prop['filename'] = $f;
                $data[] = $prop;
            }
        }
        
        
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data));
    }
    
    
    
    
    

    
}


?>