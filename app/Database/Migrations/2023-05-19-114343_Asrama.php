<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Asrama extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'asrama_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigne' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'asrama_nama' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'asrama_title_seo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'asrama_status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
            ],
            'asrama_gambar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'asrama_fasilitas' => [
                'type' => 'longtext',
            ]]);

        $this->forge->addForeignKey('username', 'admin', 'username');
        $this->forge->addKey('asrama_id', true);
        $this->forge->createTable('asrama');

    }

    public function down()
    {
        //
        $this->forge->dropTable('asrama');
    }
}
