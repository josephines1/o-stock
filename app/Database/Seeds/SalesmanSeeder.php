<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SalesmanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_cabang'         => 2,
                'nama'              => 'Budi Santoso',
                'slug'              => 'budi-santoso',
                'no_telp'           => '081234567890',
            ],
            [
                'id_cabang'         => 3,
                'nama'              => 'Rina Wijaya',
                'slug'              => 'rina-wijaya',
                'no_telp'           => '081987654321',
            ],
        ];

        // Using Query Builder
        $this->db->table('salesman')->insertBatch($data);
    }
}
