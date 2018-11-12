<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line extends CI_Controller {

	public function webhook(){
		try{
			$request = json_decode(file_get_contents('php://input'), true);

			http_response_code(200);
		}
		catch(Exception $e){
			http_response_code(500);
		}
	}
}

