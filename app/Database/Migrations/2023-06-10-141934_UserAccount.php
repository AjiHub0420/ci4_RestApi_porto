<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserAccount extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '60',
            ],
            'alamat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'profile' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default' => base_url('no-pict.png'),
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'is_admin'=>[
                'type'  => 'BOOLEAN',
                'default' => false
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_account');
    }

    public function down()
    {
        $this->forge->dropTable('user_account');
    }
}
