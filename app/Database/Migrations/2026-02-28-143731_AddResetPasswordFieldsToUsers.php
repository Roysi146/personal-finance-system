<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetPasswordFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'reset_expires_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        
        // Menambahkan kolom ke tabel users yang sudah ada
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        // Untuk membatalkan perubahan jika terjadi error
        $this->forge->dropColumn('users', 'reset_token');
        $this->forge->dropColumn('users', 'reset_expires_at');
    }
}