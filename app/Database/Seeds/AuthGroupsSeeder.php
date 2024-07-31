<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthGroupsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'          => 'pusat',
                'description'   => 'staff kantor pusat.',
            ],
            [
                'name'          => 'cabang',
                'description'   => 'staff cabang.',
            ],
        ];

        // Using Query Builder
        $this->db->table('auth_groups')->insertBatch($data);
    }
}
