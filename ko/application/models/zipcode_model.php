<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zipcode_model extends CI_Model {

    var $seq = '';
    var $zipcode = '';
    var $sido = '';
    var $gugun = '';
    var $dong = '';
    var $bunji = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select 
    function zipcodesByKeyword($keyword=null){
    	if (!$keyword)
    		return false;
    	
    	$sql = "SELECT zipcode, CONCAT( IFNULL( sido,  '' ) ,  ' ', IFNULL( gugun,  '' ) ,  ' ', IFNULL( dong,  ' ' ) ,  ' ', IFNULL( bunji,  ' ' ) ) addr_full,
CONCAT( IFNULL( sido,  '' ) ,  ' ', IFNULL( gugun,  '' ) ,  ' ', IFNULL( dong,  ' ' ) ) addr_base,delivery_fee 
FROM  `zipcode` 
WHERE dong like '%".$keyword."%' limit 30";
    	
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    function addressByZipcode($zipcode=null){
	    if (!$zipcode)
	    	return false;
	    	
	    $this->db->select("zipcode, CONCAT( IFNULL( sido,  '' ) ,  ' ', IFNULL( gugun,  '' ) ,  ' ', IFNULL( dong,  ' ' ) ,  ' ', IFNULL( bunji,  ' ' ) ) addr_full,
CONCAT( IFNULL( sido,  '' ) ,  ' ', IFNULL( gugun,  '' ) ,  ' ', IFNULL( dong,  ' ' ) ) addr_base,delivery_fee",false);
		
		$this->db->where('zipcode',$zipcode);
		
		$query = $this->db->get('zipcode');
		
		return $query->row();
    }

    

}

?>