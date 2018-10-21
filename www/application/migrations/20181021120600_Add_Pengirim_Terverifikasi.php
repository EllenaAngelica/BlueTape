<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Pengirim_Terverifikasi extends CI_Migration {

    public function up() {
        $data = array(
			'nama' => 'Shadow Bluetape',
			'email' => 'shadowbluetape@gmail.com'
		);
		$this->db->insert('Pengirim_Terverifikasi', $data);
    }

    public function down() { 
		
	}

}
