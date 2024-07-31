<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanPerProduk extends Model
{
    protected $table = 'penjualan_per_produk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_penjualan', 'id_produk', 'jumlah', 'discount', 'harga'];
    protected $useTimestamps = true;

    // Mengambil data penjualan per produk berdasarkan id penjualan
    public function getPenjualanPerProduk($id_penjualan, $perPage = false, $offset = false)
    {
        $db      = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('penjualan_per_produk.*, produk.nama as nama_produk, produk.kode_produk');
        $builder->join('produk', 'produk.id = penjualan_per_produk.id_produk');
        $builder->where('id_penjualan', $id_penjualan);
        $builder->limit($perPage, $offset); // Paginasi

        $result = $builder->get()->getResultArray();

        return $result;
    }
}
