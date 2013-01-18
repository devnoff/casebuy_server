<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'action.php';

class Question extends Action {


	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('members_model');
        $this->load->model('questions_model');
        $this->load->database();
        
        
	}
	
	
	/*
	 * 질답 아이템
	 * 
	 */
	function qnaPair($id){
	    
		$result['question'] = $this->questions_model->question($id);
		$result['answer'] = $this->questions_model->answer($id);
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	
    /*
     * 답변 등록
     *
     */
    function addAnswer(){
        $questions_id = $this->input->post('questions_id');
        $content = $this->input->post('content');
        
        $result = $this->questions_model->addAnswer($questions_id,$content);
        
        if ($result){
            echo '{"success": true, "answer_id":'.$result.'}';
        } else {
            echo '{"success": false}';
        }
    }
	
	
	/*
     * 답변 업데이트
     *
     */
    function updateAnswer(){
        $questions_id = $this->input->post('answer_id');
        $content = $this->input->post('content');
        
        $result = $this->questions_model->updateAnswer($questions_id,$content);
        
        if ($result){
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }

    function removeQna(){
        $id = $this->input->post('id');

        $result = $this->questions_model->delete($id);
        
        if ($result){
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }
}


?>