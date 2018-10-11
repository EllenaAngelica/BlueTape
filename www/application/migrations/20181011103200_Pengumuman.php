<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Pengumuman extends CI_Migration {

    public function up() {
        $fields = array(
            'id' => array(
                'type' => 'int',
				 'auto_increment' => TRUE
            ),
            'email_id' => array(
                'type' => 'int'
            ),
			'waktu_terkirim' => array(
                'type' => 'timestamp'
            ),
			'subjek' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
			'isi' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
			'ketersediaan_lampiran' => array(
				'type' => 'VARCHAR',
                'constraint' => '1'
			),
			'slug' => [
				'type' => 'VARCHAR',
				'constraint' => '256',
				'after' => 'title'
			]
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('Pengumuman');
		$this->db->set('slug', 'id', FALSE);
		$this->db->update('Pengumuman');
    }

    public function down() { 
		
	}

}
