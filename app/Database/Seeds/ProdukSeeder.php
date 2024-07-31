<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_kategori'       => 1,
                'id_supplier'       => 1,
                'kode_produk'       => 'TLED42',
                'nama'              => 'Televisi LED 42"',
                'slug'              => 'televisi-led-42',
                'harga_jual'        => 5000000,
            ],
            [
                'id_kategori'       => 2,
                'id_supplier'       => 2,
                'kode_produk'       => 'MCTBG1',
                'nama'              => 'Mesin Cuci 1 Tabung',
                'slug'              => 'mesin-cuci-1-tabung',
                'harga_jual'        => 2500000,
            ],
        ];

        // Using Query Builder
        $this->db->table('produk')->insertBatch($data);
    }
}
