<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Pengirim_Terverifikasi extends CI_Migration {

    public function up() {
        $fields = array(
            'id' => array(
                'type' => 'int',
				 'auto_increment' => TRUE
            ),
            'nama' => array(
                'type' => 'VARCHAR',
                'constraint' => '1024'
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '1024'
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('Pengirim_Terverifikasi');
    }

    public function down() { 
		
	}

}
