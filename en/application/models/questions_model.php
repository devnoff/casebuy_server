<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questions_model extends CI_Model {

    var $id = null;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load Database
        $this->load->database();
    }
    
    
    // All Questions
    function questions($limit=10,$offset=0,$state=null, $date=null, $month=null, $keyword=null){
        $sql = "select * from (select 
        *, (select count(id) from questions where questions_id=q.id) as reply, 
        (select username from members where id=q.members_id) username,
        (select title from products where id=q.products_id) product_name
        from
        (select * from questions where questions_id is null) q )a ";
        
        $q = array();
        if ($state=='not_replied'){
            $q[] = 'reply < 1';
        }
        if ($date!=null){
            $q[] = "date_format(date_write, '%Y-%m-%d') = '$date' ";
        }
        
        if ($month!=null){
            $q[] = "date_format(date_write, '%m/%Y') = '$month' ";
        }
        
        if ($keyword!=null){
            $q[] = "(username like '%$keyword%' or a.product_name like '%$keyword%') ";
        }
        
        if ($state=='not_replied' || $date || $month || $keyword){
            $sql .= "where ";
            $q = join('and ',$q);
            $sql .= $q;
        }
        
        $sql .= " order by a.family desc, orderby limit $offset,$limit ";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }

    function countQuestions($state=null, $date=null, $month=null, $keyword=null){
      $sql = "select count(*) cnt from (select 
        *, (select count(id) from questions where questions_id=q.id) as reply, 
        (select username from members where id=q.members_id) username,
        (select title from products where id=q.products_id) product_name
        from
        (select * from questions where questions_id is null) q )a ";
        
        $q = array();
        if ($state=='not_replied'){
            $q[] = 'reply < 1';
        }
        if ($date!=null){
            $q[] = "date_format(date_write, '%Y-%m-%d') = '$date' ";
        }
        
        if ($month!=null){
            $q[] = "date_format(date_write, '%m/%Y') = '$month' ";
        }
        
        if ($keyword!=null){
            $q[] = "(username like '%$keyword%' or a.product_name like '%$keyword%') ";
        }
        
        if ($state=='not_replied' || $date || $month || $keyword){
            $sql .= "where ";
            $q = join('and ',$q);
            $sql .= $q;
        }
        
        $sql .= " order by a.family desc, orderby";
        
        $query = $this->db->query($sql);
        
        return $query->row()->cnt;
    }
    
    // Question Item
    function question($id){
        $sql = "select 
        *, (select count(id) from questions where questions_id=q.id) reply, 
        (select username from members where id=q.members_id) username,
        (select title from products where id=q.products_id) product_name
        from
        (select * from questions where questions_id is null) q where id=$id";
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    // Answer Item
    function answer($id){
        $query = $this->db->get_where('questions',array('questions_id'=>$id));
        return $query->row();
    }
    
    
    // Add Answer
    function addAnswer($target_id, $content){
        if (!$target_id || !$content)
            return false;
            
        $query = $this->db->get_where('questions',array('id'=>$target_id));
        $parent = $query->row();
            
        $this->db->trans_begin();
          
          $family = $parent->family;
          
          $this->db->select_max('orderby','max_order');
          $this->db->where('family',$parent->family);
          $orderby = $this->db->get('questions')->row()->max_order + 1;
        
          $step = 1;
              
          $data = array(
              'questions_id'=>$target_id,
              'products_id'=>$parent->products_id,
              'content'=>$content,
              'family'=>$family,
              'orderby'=>$orderby,
              'step'=>$step);
          
          $this->db->set('date_write','NOW()',FALSE);
          
          $this->db->insert('questions',$data);
          $insert_id = $this->db->insert_id();
          
          if ($this->db->trans_status() === FALSE)
          {
              $this->db->trans_rollback();
              return false;
          }
          $this->db->trans_commit();
          return $insert_id;
    }
    
    // Update Answer
    function updateAnswer($id, $content){
        if (!$id || !$content)
            return false;
            
        $data = array('content'=>$content);
            
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->update('questions',$data);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        
        $this->db->trans_commit();
        return true;
    }


    function delete($id){
        if (!$id)
            return false;
            
        $this->db->trans_begin();
        $this->db->delete('questions',array('id'=>$id));
        $this->db->delete('questions',array('questions_id'=>$id));
        
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