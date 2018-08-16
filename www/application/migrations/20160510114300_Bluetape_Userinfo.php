<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Bluetape_Userinfo extends CI_Migration {

    public function up() {
        $fields = array(
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '128'
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'lastUpdate' => array(
                'type' => 'timestamp'
            ),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('email', TRUE);
        $this->dbforge->create_table('Bluetape_Userinfo');

        $this->dbforge->drop_column('Transkrip', 'requestByName');
    }

    public function down() { }

}
