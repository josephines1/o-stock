<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    // Nama tabel yang digunakan dalam model ini
    protected $table = 'supplier';

    // Kunci utama tabel
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi (allowed fields)
    protected $allowedFields = ['nama', 'slug'];

    // Menentukan apakah model menggunakan timestamps untuk kolom created_at dan updated_at
    protected $useTimestamps = true;

    /**
     * Mengambil data supplier dengan opsi pagination dan filter.
     *
     * @param string|false $slug Jika disediakan, hanya data dengan slug ini yang akan diambil
     * @param array|false $filter Opsi filter yang dapat diterapkan pada query
     * @param int $perPage Jumlah item per halaman untuk pagination
     * @return array Daftar supplier dan informasi pagination
     */
    public function getSupplier($slug = false, $filter = false, $perPage = 10)
    {
        // Mengambil layanan pager untuk pagination
        $pager = service('pager');
        $pager->setPath('supplier', 'supplier'); // Menentukan path untuk pagination

        // Mengambil halaman saat ini dari query parameter atau default ke 1
        $page = (@$_GET['page_supplier']) ? $_GET['page_supplier'] : 1;
        $offset = ($page - 1) * $perPage; // Menghitung offset untuk query pagination

        // Menghubungkan ke database dan memulai query builder
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);

        // Memilih kolom yang akan diambil dari tabel supplier
        $builder->select('supplier.*');
        // Mengurutkan hasil berdasarkan nama supplier secara ascending
        $builder->orderBy('nama', 'ASC');

        // Menginisialisasi variabel total untuk menghitung total data
        $total = 0;

        // Menghitung total data tanpa filter
        $countQuery = clone $builder;
        $total = $countQuery->countAllResults();

        // Jika slug disediakan, menghitung total data berdasarkan slug
        if ($slug) {
            $countQuery = clone $builder;
            $total = $countQuery->where('slug', $slug)->countAllResults();
        } else if ($filter) {
            // Jika filter disediakan, terapkan filter pada query
            $filter_keyword = $filter['keyword'];

            // Jika ada keyword filter, tambahkan kondisi like pada nama
            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('nama', $filter_keyword)
                    ->groupEnd();
            }

            // Menghitung total data setelah diterapkan filter
            $countQuery = clone $builder;
            $total = $countQuery->countAllResults();
        }

        if ($slug) {
            // Mengambil data berdasarkan slug dengan pagination
            $result = $builder->where('slug', $slug)->get($perPage, $offset)->getResultArray();
        } else {
            // Mengambil data tanpa filter dengan pagination
            $result = $builder->get($perPage, $offset)->getResultArray();
        }

        // Mengembalikan array berisi data supplier, informasi pagination, dan total data
        return [
            'supplier' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'supplier'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }
}
