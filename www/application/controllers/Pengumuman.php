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
			$this->Pengumuman_model->checkEmail();
			echo '</br><a href="'.base_url('/Pengumuman').'">Back to BlueTape</a>';
		} catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
    }
}