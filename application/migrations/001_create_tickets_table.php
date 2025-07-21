<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tickets_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'ticket_id' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'sender' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'date' => [
                'type' => 'DATETIME'
            ],
            'content' => [
                'type' => 'TEXT'
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tickets');
    }

    public function down() {
        $this->dbforge->drop_table('tickets');
    }
} 