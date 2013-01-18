<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_points_model extends CI_Model {

    var $id = '';
    var $members_id = '';
    var $point = '';
    var $ref_orders_id = null;
    var $reason = 'EARN_TO_JOIN';
    var $date_added = '';
    var $date_modified = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    // Select Sum Point
    function pointsByMember($id=null){
    	if (!$id)
    		return false;
    	
    	$this->db->select('sum(point) as sum_point', false);
    	$query = $this->db->get_where('member_points',array('members_id'=>$id));
    	
        
        return $query->row()->sum_point;
    }
    
    function pointListByMember($id=null, $limit=100, $offset=0){
    	if (!$id)
    		return false;
    		
    	$this->db->select("member_points.id, date_format(date_added, '%Y-%m-%d') as date_added, if(point>0,'+','') as mark, format(point,0) point, reason, ref_orders_id, order_code", false);
    	$this->db->from('member_points');
    	$this->db->join('orders','member_points.ref_orders_id = orders.id','left');
    	$this->db->where('member_points.members_id',$id);
    	$this->db->order_by('member_points.id', 'desc');
    	$this->db->limit($limit, $offset);
    	$query = $this->db->get();
    	
    	return $query->result();
    }

    // Insert
    function insert(){
        
        $data = array (
        	'members_id'=>$this->members_id,
        	'point'=>$this->point,
        	'reason'=>$this->reason
        	);
        
        if ($this->ref_orders_id){
	    	$this->db->set('ref_orders_id',$this->ref_orders_id);    
        }
        
        $this->db->set('date_added','NOW()',false);
        
        $this->db->trans_begin();
        $this->db->insert('member_points', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }


}

?>