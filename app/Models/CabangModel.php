<?php

namespace App\Models;

use CodeIgniter\Model;

class CabangModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table = 'kantor';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['nama', 'alamat', 'slug', 'tipe', 'kode_cabang'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    /*
    * Mengambil data cabang berdasarkan slug, filter, atau untuk
    */
    public function getCabang($slug = false, $filter = false, $perPage = 10)
    {
        // Mendapatkan layanan pager dari CodeIgniter 4
        $pager = service('pager');

        // Menetapkan path dasar untuk pager
        $pager->setPath('cabang', 'cabang');

        // Mendapatkan halaman saat ini dari query string atau default ke 1
        $page = (@$_GET['page_cabang']) ? $_GET['page_cabang'] : 1;
        $offset = ($page - 1) * $perPage;

        // Menghubungkan ke database dan mendapatkan builder untuk tabel kantor
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('kantor.*');
        $builder->orderBy('tipe', 'DESC');
        $builder->orderBy('nama', 'ASC');

        // Inisialisasi total hasil
        $total = 0;

        // Membuat clone dari builder untuk menghitung total hasil
        $countQuery = clone $builder;
        $total = $countQuery->countAllResults();

        if ($slug) {
            // Jika slug diberikan, filter berdasarkan slug
            $countQuery = clone $builder;
            $total = $countQuery->where('slug', $slug)->countAllResults();
        } else if ($filter) {
            // Jika filter diberikan, filter berdasarkan keyword
            $filter_keyword = $filter['keyword'];

            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('kantor.nama', $filter_keyword)
                    ->groupEnd();
            }

            // Membuat clone dari builder untuk menghitung total hasil setelah filter
            $countQuery = clone $builder;
            $total = $countQuery->countAllResults();
        }

        if ($slug) {
            // Jika slug diberikan, mengambil hasil berdasarkan slug
            $result = $builder->where('slug', $slug)->get($perPage, $offset)->getResultArray();
        } else {
            // Mengambil hasil berdasarkan halaman dan offset
            $result = $builder->get($perPage, $offset)->getResultArray();
        }

        // Mengembalikan hasil query beserta pagination links
        return [
            'cabang' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'cabang'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    /*
    * Mengambil total jumlah cabang
    */
    public function getTotalCabang()
    {
        // Menghubungkan ke database dan mendapatkan builder untuk tabel kantor
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('kantor.*');
        $builder->where('tipe', 'cabang');

        // Menghitung total hasil
        return $builder->countAllResults();
    }
}
