<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesmanModel extends Model
{
    // Nama tabel yang digunakan dalam model ini
    protected $table      = 'salesman';

    // Kunci utama tabel
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi (allowed fields)
    protected $allowedFields = ['nama', 'slug', 'no_telp', 'id_cabang'];

    // Menentukan apakah model menggunakan timestamps untuk kolom created_at dan updated_at
    protected $useTimestamps = true;

    /**
     * Mengambil data salesman dengan opsi pagination dan filter.
     *
     * @param string|false $slug Jika disediakan, hanya data dengan slug ini yang akan diambil
     * @param array|false $filter Opsi filter yang dapat diterapkan pada query
     * @param int $perPage Jumlah item per halaman untuk pagination
     * @return array Daftar salesman dan informasi pagination
     */
    public function getSalesman($slug = false, $filter = false, $perPage = 10)
    {
        // Mengambil layanan pager untuk pagination
        $pager = service('pager');
        $pager->setPath('salesman', 'salesman'); // Menentukan path untuk pagination

        // Mengambil halaman saat ini dari query parameter atau default ke 1
        $page = (@$_GET['page_salesman']) ? $_GET['page_salesman'] : 1;
        $offset = ($page - 1) * $perPage; // Menghitung offset untuk query pagination

        // Menghubungkan ke database dan memulai query builder
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);

        // Memilih kolom yang akan diambil dari tabel salesman
        $builder->select('salesman.*, kantor.nama as nama_cabang');
        // Bergabung dengan tabel kantor untuk mendapatkan nama cabang
        $builder->join('kantor', 'kantor.id = salesman.id_cabang');
        // Mengurutkan hasil berdasarkan nama salesman secara ascending
        $builder->orderBy('nama', 'ASC');

        // Menginisialisasi variabel total untuk menghitung total data
        $total = 0;

        // Menghitung total data tanpa filter
        $countQuery = clone $builder;
        $total = $countQuery->countAllResults();

        // Jika slug disediakan, menghitung total data berdasarkan slug
        if ($slug) {
            $countQuery = clone $builder;
            $total = $countQuery->where('salesman.slug', $slug)->countAllResults();
        } else if ($filter) {
            // Jika filter disediakan, terapkan filter pada query
            $filter_keyword = $filter['keyword'];
            $filter_cabang = $filter['cabang'];

            // Jika ada keyword filter, tambahkan kondisi like pada nama
            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('salesman.nama', $filter_keyword)
                    ->groupEnd();
            }

            // Jika filter cabang tidak sama dengan 0, tambahkan kondisi where pada id_cabang
            if ($filter_cabang != 0) {
                $builder->groupStart()
                    ->where('id_cabang', $filter_cabang)
                    ->groupEnd();
            }

            // Menghitung total data setelah diterapkan filter
            $countQuery = clone $builder;
            $total = $countQuery->countAllResults();
        }

        if ($slug) {
            // Mengambil data berdasarkan slug dengan pagination
            $result = $builder->where('salesman.slug', $slug)->get($perPage, $offset)->getResultArray();
        } else {
            // Mengambil data tanpa filter dengan pagination
            $result = $builder->get($perPage, $offset)->getResultArray();
        }

        // Mengambil total penjualan per salesman
        foreach ($result as &$salesman) {
            $salesman['total_penjualan'] = $this->getSalesmanSales($salesman['id']);
        }

        // Mengembalikan array berisi data salesman, informasi pagination, dan total data
        return [
            'salesman' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'salesman'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    /**
     * Mengambil jumlah total penjualan untuk seorang salesman dalam bulan dan tahun ini.
     *
     * @param int $salesmanId ID salesman
     * @return int Jumlah total penjualan
     */
    public function getSalesmanSales($salesmanId)
    {
        // Menghubungkan ke database dan memulai query builder
        $db = \Config\Database::connect();
        $builder = $db->table('penjualan');
        $builder->select('COUNT(*) as total_penjualan');

        // Menambahkan kondisi untuk id_salesman dan waktu
        $builder->where('id_salesman', $salesmanId);
        $builder->where('MONTH(tanggal)', date('m'));
        $builder->where('YEAR(tanggal)', date('Y'));
        $query = $builder->get();
        $result = $query->getRow();

        // Mengembalikan total penjualan
        return $result->total_penjualan;
    }
}
