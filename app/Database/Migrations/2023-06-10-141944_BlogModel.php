<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BlogModel extends Migration
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
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '60',
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default' => base_url('no-pict.png'),
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'contents' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'       => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
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
        $this->forge->createTable('blog');
    }

    public function down()
    {
        $this->forge->dropTable('blog');
    }
}
