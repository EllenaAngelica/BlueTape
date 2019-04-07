<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function daily() {
		try {
			$this->load->model('Pengumuman_model');
			$newEmails = $this->Pengumuman_model->checkEmail();
			if($newEmails != null){
				foreach($newEmails as $newEmail):
					$this->Pengumuman_model->proceedEmail($newEmail);
				endforeach;
			}
		} catch (Exception $e) {
			$this->Log_model->error("Problem in executing cronjob: " . $e->getMessage(), $e->getTrace());
			http_response_code(500);
			echo json_encode($e->getMessage());
		}
	}
}

