<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
    * Get image properties
    *
    * A helper function that gets info about the file
    *
    * @access    public
    * @param    string
    * @return    mixed
    */            
   function get_image_properties($path = '', $return = FALSE)
   {
       // For now we require GD but we should
       // find a way to determine this using IM or NetPBM
       
       if ($path == '')
           $path = $this->full_src_path;
               
       if ( ! file_exists($path))
       {
           $this->set_error('imglib_invalid_path');        
           return FALSE;                
       }
       
       $vals = @getimagesize($path);
       
       $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
       
       $mime = (isset($types[$vals['2']])) ? 'image/'.$types[$vals['2']] : 'image/jpg';
               
       if ($return == TRUE)
       {
           $v['filepath'] = $path;
           $v['width']            = $vals['0'];
           $v['height']        = $vals['1'];
           $v['image_type']    = $vals['2'];
           // $v['size_str']        = $vals['3'];
           $v['mime_type']        = $mime;
           
           return $v;
       }
       
       $this->orig_width    = $vals['0'];
       $this->orig_height    = $vals['1'];
       $this->image_type    = $vals['2'];
       $this->size_str        = $vals['3'];
       $this->mime_type    = $mime;
       
       return TRUE;
   }
   
   
?>