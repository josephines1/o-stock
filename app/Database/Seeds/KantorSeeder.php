<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KantorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_cabang'      => 'KPST',
                'tipe'             => 'pusat',
                'nama'             => 'Kantor Pusat ABC',
                'slug'             => 'kantor-pusat-abc',
                'alamat'           => 'Jl. Sudirman No. 123',
            ],
            [
                'kode_cabang'      => 'CJKS',
                'tipe'             => 'cabang',
                'nama'             => 'JakSel Elektronik',
                'slug'             => 'jaksel-elektronik',
                'alamat'           => 'Jl. Fatmawati No. 456',
            ],
            [
                'kode_cabang'      => 'CBDG',
                'tipe'             => 'cabang',
                'nama'             => 'Bandung Elektronik',
                'slug'             => 'bandung-elektronik',
                'alamat'           => 'Jl. Asia Afrika No. 789',
            ],
        ];

        // Using Query Builder
        $this->db->table('kantor')->insertBatch($data);
    }
}
