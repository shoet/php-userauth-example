<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => false,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        //
    }
}
