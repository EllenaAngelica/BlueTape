<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line extends CI_Controller {

	public function webhook(){
		try{
			$this->load->model('Line_model');
			
			$xLineSignature = $_SERVER['HTTP_LINE_SIGNATURE'];
			$httpPostRequestBody = file_get_contents('php://input');
			
			if (empty($signature)) {
				http_response_code(400); // Bad Request, Signature is Missing
			}
			else{
				$this->Pengumuman_model->proceedWebhook($xLineSignature, $httpRequestBody);
			}

			http_response_code(200);
		}
		catch(Exception $e){
			http_response_code(500);
		}
	}
}

