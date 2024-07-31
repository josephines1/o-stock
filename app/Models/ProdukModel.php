<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_produk', 'nama', 'slug', 'harga_jual', 'id_kategori', 'id_supplier'];
    protected $useTimestamps = true;

    /**
     * Mengambil data produk dengan filter dan paginasi
     *
     * @param string|false $slug Jika disediakan, hanya data dengan slug ini yang akan diambil
     * @param array|false $filter Opsi filter yang dapat diterapkan pada query
     * @param int $perPage Jumlah item per halaman untuk pagination
     * @return array Daftar produk dan informasi pagination
     */
    public function getProduk($slug = false, $filter = false, $perPage = 10)
    {
        $pager = service('pager');
        $pager->setPath('produk', 'produk');

        $page = (@$_GET['page_produk']) ? $_GET['page_produk'] : 1;
        $offset = ($page - 1) * $perPage;

        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('
            produk.*, 
            supplier.nama as nama_supplier, 
            kategori_produk.nama as nama_kategori
        ');
        $builder->join('supplier', 'supplier.id = produk.id_supplier');
        $builder->join('kategori_produk', 'kategori_produk.id = produk.id_kategori');
        $builder->orderBy('nama', 'ASC');

        $total = 0;

        // Menghitung total hasil
        $countQuery = clone $builder;
        $total = $countQuery->countAllResults();

        if ($slug) {
            $countQuery = clone $builder;
            $total = $countQuery->where('produk.slug', $slug)->countAllResults();
        } else if ($filter) {
            $filter_keyword = $filter['keyword'];
            $filter_kategori = $filter['kategori'];
            $filter_supplier = $filter['supplier'];

            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('produk.nama', $filter_keyword)
                    ->orLike('produk.kode_produk', $filter_keyword)
                    ->groupEnd();
            }

            if ($filter_kategori != 0) {
                $builder->groupStart()
                    ->where('produk.id_kategori', $filter_kategori)
                    ->groupEnd();
            }

            if ($filter_supplier != 0) {
                $builder->groupStart()
                    ->where('produk.id_supplier', $filter_supplier)
                    ->groupEnd();
            }

            $countQuery = clone $builder;
            $total = $countQuery->countAllResults();
        }

        if ($slug) {
            $result = $builder->where('produk.slug', $slug)->get($perPage, $offset)->getResultArray();
        } else {
            $result = $builder->get($perPage, $offset)->getResultArray();
        }

        return [
            'produk' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'produk'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    // Mengambil total produk
    public function getTotalProduk()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('produk.*');

        return $builder->countAllResults();
    }
}
