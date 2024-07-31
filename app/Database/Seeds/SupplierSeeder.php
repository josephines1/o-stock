<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'              => 'PT. Sumber Sejahtera',
                'slug'              => 'pt-sumber-sejahtera',
            ],
            [
                'nama'              => 'CV. Maju Bersama',
                'slug'              => 'cv-maju-bersama',
            ],
        ];

        // Using Query Builder
        $this->db->table('supplier')->insertBatch($data);
    }
}
