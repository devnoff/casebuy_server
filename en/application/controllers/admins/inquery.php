<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'action.php';

class Inquery extends Action {


	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('adult');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->model('members_model');
        $this->load->model('inqueries_model');
        $this->load->database();
        
        
	}
	
	
	/*
	 * 질답 아이템
	 * 
	 */
	function qnaPair($id){
	    
		$result['question'] = $this->inqueries_model->question($id);
		$result['answer'] = $this->inqueries_model->answer($id);
		
		$this->output
			 ->set_content_type('application/json')
			 ->set_output(json_encode($result));
	}
	
	
    /*
     * 답변 등록
     *
     */
    function addAnswer(){
        $inqueries_id = $this->input->post('inqueries_id');
        $content = $this->input->post('content');

        $inq = $this->db->get_where('inqueries',array('inqueries_id'=>$inqueries_id))->row();
        if ($inq){
            $this->input->post('answer_id') = $inq->id;
            $this->updateAnswer();
            return;
        }


        $result = $this->inqueries_model->addAnswer($inqueries_id,$content);
        
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
        $inqueries_id = $this->input->post('answer_id');
        $content = $this->input->post('content');
        
        $result = $this->inqueries_model->updateAnswer($inqueries_id,$content);
        
        if ($result){
            echo '{"success": true}';
        } else {
            echo '{"success": false}';
        }
    }
}


?>