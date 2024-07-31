<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'              => 'Elektronik',
                'slug'              => 'elektronik',
            ],
            [
                'nama'              => 'Peralatan Rumah Tangga',
                'slug'              => 'peralatan-rumah-tangga',
            ],
        ];

        // Using Query Builder
        $this->db->table('kategori_produk')->insertBatch($data);
    }
}
