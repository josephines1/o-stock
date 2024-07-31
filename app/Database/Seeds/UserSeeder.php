<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_kantor'         => 1,
                'email'             => 'pusat@example.com',
                'fullname'          => 'Pusat',
                'username'          => 'pusat',
                'password_hash'     => '$2y$10$6NhRCBZTsHNQqkRTMfZbJ.Z6Q2RlamYguwa0caxtrwEQAJz82CYP.',
                'active'            => 1,
            ],
            [
                'id_kantor'         => 2,
                'email'             => 'cabang1@example.com',
                'fullname'          => 'Cabang 1',
                'username'          => 'cabang1',
                'password_hash'     => '$2y$10$6NhRCBZTsHNQqkRTMfZbJ.Z6Q2RlamYguwa0caxtrwEQAJz82CYP.',
                'active'            => 1,
            ],
            [
                'id_kantor'         => 3,
                'email'             => 'cabang2@example.com',
                'fullname'          => 'Cabang 2',
                'username'          => 'cabang2',
                'password_hash'     => '$2y$10$6NhRCBZTsHNQqkRTMfZbJ.Z6Q2RlamYguwa0caxtrwEQAJz82CYP.',
                'active'            => 1,
            ],
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);
    }
}
