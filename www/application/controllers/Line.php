<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line extends CI_Controller {

	public function webhook(){
		try{
			$this->load->model('Line_model');
			
			$httpPostRequestBody = file_get_contents('php://input');
			$xLineSignature = $this->input->get_request_header('X-Line-Signature');
			
			if (empty($xLineSignature)) {
				http_response_code(400); // Bad Request, Signature is Missing
			}
			else{
				$this->Pengumuman_model->proceedWebhook($httpRequestBody, $xLineSignature);
			}

			http_response_code(200);
		}
		catch(Exception $e){
			http_response_code(500);
		}
	}
}

