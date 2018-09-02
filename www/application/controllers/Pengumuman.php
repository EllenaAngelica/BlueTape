<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('BlueTape');
		$this->load->model('Pengumuman_model');
        $this->load->database();
    }

    public function index() {
        // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
		
        $this->load->view('Pengumuman/main', array(
            'currentModule' => get_class()
        ));
    }
	
    public function pushNotification() {
		try{
			echo 'History ID: ' . $_SESSION['hId'] .'</br>'; 
			$r = $this->Pengumuman_model->refreshNotification();
			echo 'New messages : </br>';
			echo $r;
			echo '</br>';
			echo '</br>';
			
			echo 'Last five messages : </br>';
			$r2 = $this->Pengumuman_model->getMessageList();
			$messages = explode('{',$r2);
			$i=1;
			while($i<=5){
				$m = explode('"',$messages[$i+1])[3];
				echo 'Message </br>';
				echo 'id : ' . $m . '</br>';
				$dataM = $this->Pengumuman_model->getInformation($m);
				$dataMDeliveredTo = explode('"', explode('{',$dataM)[3])[7];
				echo 'Delivered-To : ' . $dataMDeliveredTo . '</br>';
				$dataMFrom = explode('"', explode('{',$dataM)[4])[7];
				echo 'From : ' . $dataMFrom . '</br>';
				$dataMDate = explode('"', explode('{',$dataM)[5])[7];
				echo 'Date : ' . $dataMDate . '</br>';
				$dataMSubject = explode('"', explode('{',$dataM)[6])[7];
				echo 'Subject : ' . $dataMSubject . '</br>';
				echo '</br>';
				$i++;
			}
			echo '</br>';
			echo '</br><a href="'.base_url('/Pengumuman').'">Back to BlueTape</a>';
		} catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
    }
}