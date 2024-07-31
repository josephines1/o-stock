<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KonsumenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_cabang'         => 2,
                'nama'              => 'PT. Sinar Jaya',
                'slug'              => 'pt-sinar-jaya',
                'alamat'            => 'Jl. Merdeka No. 123',
                'kota'              => 'Jakarta',
                'no_telp'           => '0214567890',
            ],
            [
                'id_cabang'         => 3,
                'nama'              => 'CV. Makmur Abadi',
                'slug'              => 'cv-makmur-abadi',
                'alamat'            => ' Jl. Diponegoro No. 456',
                'kota'              => 'Bandung',
                'no_telp'           => '0225678901',
            ],
        ];

        // Using Query Builder
        $this->db->table('konsumen')->insertBatch($data);
    }
}
