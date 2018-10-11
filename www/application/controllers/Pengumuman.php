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
		
		$this->db->select();
		$this->db->order_by('id', 'desc');
		$query = $this->db->get('Pengumuman');
		$announcements = $query->result_array();
		foreach ($announcements as &$announcement) {
			$announcement['url'] = "/pengumuman/read/" . $announcement['slug'];
		}
		
        $this->load->view('Pengumuman/main', array(
            'currentModule' => get_class()
        ));
    }
	
	public function read($slug){		
		$this->db->where('slug', $slug);
		$this->db->select('*');
		$this->db->from('Pengumuman');
		$this->db->join('Pengirim_Terverifikasi', 'Pengirim_Terverifikasi.id = Pengumuman.email_id');
		$query = $this->db->get();
		$pengumuman= $query->row_array();
		if ($pengumuman === NULL) {
			show_404();
			exit;
		}
		$this->load->view('pengumuman', array(
			'pengumuman' => $pengumuman
		));
	}
}