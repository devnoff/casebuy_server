<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH.'helpers/secure.php');

class Action extends CI_Controller {

    var $secureMgr;

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->secureMgr = new Secure();
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('members_model');
        $this->load->database();
        
        $this->checkAdmin();
        
	}
	
	protected function adminCheck(){
	    if (!$this->members_model->adminValid()){
            
            // 관리자가 아닐경우
    	    $data['redirect_url'] = site_url('admin/main/top_sales');
            $this->load->view('member/login_view',$data);
    	    return false;
	    }
	    
	    return true;
	}
	
	/* 보안 객체 */
    protected function secure(){
        if ($this->secureMgr==null){
            $this->secureMgr = new Secure();
        }
           
        return $this->secureMgr;
    }
    
    private function checkAdmin (){
	    if (!$this->members_model->adminValid()){
		    redirect('admins/fail');
	    }
    }
	
	
	function test(){
		echo '<script>alert("hello");</script>';
	}


	/*
	 * 성지식
	 */

	function addKnowledge(){
		$data = $this->input->post();

		$this->load->model('knowledge_model');

		$result['success'] = false;
		if ($this->knowledge_model->insert($data)){
			$result['success'] = true;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}

	function updateKnowledge(){
		$data = $this->input->post();

		$this->load->model('knowledge_model');

		$result['success'] = false;
		if ($this->knowledge_model->update($data)){
			$result['success'] = true;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}

	function removeKnowledge(){
		$id = $this->input->post('id');

		$this->load->model('knowledge_model');

		$result['success'] = false;
		if ($this->knowledge_model->delete($id)){
			$result['success'] = true;
		}

		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}


	function addNotice(){

		$title = $this->input->post('title');
		$content = $this->input->post('content');

		$result['success'] = false;

		if (!$title || !$content){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}

		$data['title'] = $title;
		$data['content'] = $content;
        
        $this->db->trans_begin();
        $this->db->insert('notice', $data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else {
			$this->db->trans_commit();
			$result['success'] = true;	
        }

        $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}


	function removeNotice(){

		$id = $this->input->post('id');
		$result['success'] = false;

		if (!$id){
			$this->output
				 ->set_content_type('application/json')
				 ->set_output(json_encode($result));
		}

		$this->db->trans_begin();
        $this->db->delete('notice', array('id'=>$id));

    	if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else {
        	$this->db->trans_commit();
        	$result['success'] = true;
        }

        $this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));

	}

}


?>