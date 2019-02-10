<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Line_Followers_initial extends CI_Migration {

    public function up() {
        $fields = array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('userId', TRUE);
		$this->dbforge->create_table('Line_Followers');
    }

    public function down() { 
		
	}

}
