<?php

namespace App\Models;

use CodeIgniter\Model;

class KonsumenModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table      = 'konsumen';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['nama', 'slug', 'alamat', 'kota', 'no_telp', 'id_cabang'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    // Fungsi untuk mendapatkan data konsumen dengan opsi filter, slug, dan paginasi
    public function getKonsumen($slug = false, $filter = false, $perPage = 10)
    {
        $pager = service('pager'); // Mengambil layanan pager
        $pager->setPath('konsumen', 'konsumen'); // Menetapkan path untuk pager

        // Mengambil halaman saat ini dari parameter GET atau default ke halaman 1
        $page = (@$_GET['page_konsumen']) ? $_GET['page_konsumen'] : 1;
        $offset = ($page - 1) * $perPage; // Menghitung offset untuk paginasi

        // Menghubungkan ke database dan membuat builder untuk tabel konsumen
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('konsumen.*, kantor.nama as nama_cabang');
        $builder->join('kantor', 'kantor.id = konsumen.id_cabang');
        $builder->orderBy('nama', 'ASC');

        // Variabel untuk menyimpan total hasil
        $total = 0;

        // Menghitung total hasil berdasarkan query
        $countQuery = clone $builder; // Mengkloning builder untuk menghitung total
        $total = $countQuery->countAllResults(); // Menghitung total hasil

        if ($slug) {
            // Jika slug disediakan, hitung total berdasarkan slug
            $countQuery = clone $builder; // Mengkloning builder untuk menghitung total berdasarkan slug
            $total = $countQuery->where('konsumen.slug', $slug)->countAllResults(); // Menghitung total hasil untuk slug tertentu
        } else if ($filter) {
            // Jika filter disediakan, tambahkan filter ke query
            $filter_keyword = $filter['keyword'];
            $filter_cabang = $filter['cabang'];

            if ($filter_keyword) {
                // Jika ada keyword filter, tambahkan kondisi LIKE pada nama dan kota
                $builder->groupStart()
                    ->like('konsumen.nama', $filter_keyword)
                    ->orLike('konsumen.kota', $filter_keyword)
                    ->groupEnd();
            }

            if ($filter_cabang != 0) {
                // Jika ada filter cabang, tambahkan kondisi untuk id_cabang
                $builder->groupStart()
                    ->where('konsumen.id_cabang', $filter_cabang)
                    ->groupEnd();
            }

            // Menghitung total hasil berdasarkan filter
            $countQuery = clone $builder; // Mengkloning builder untuk menghitung total berdasarkan filter
            $total = $countQuery->countAllResults(); // Menghitung total hasil dengan filter
        }

        if ($slug) {
            // Jika slug disediakan, ambil hasil untuk slug tertentu dengan paginasi
            $result = $builder->where('konsumen.slug', $slug)->get($perPage, $offset)->getResultArray(); // Mengambil hasil untuk slug tertentu
        } else {
            // Jika tidak ada slug, ambil hasil dengan paginasi
            $result = $builder->get($perPage, $offset)->getResultArray(); // Mengambil hasil dengan paginasi
        }

        // Menghasilkan data konsumen, link paginasi, total hasil, per halaman, dan halaman saat ini
        return [
            'konsumen' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'konsumen'), // Membuat link paginasi
            'total' => $total, // Total hasil
            'perPage' => $perPage, // Jumlah hasil per halaman
            'page' => $page, // Halaman saat ini
        ];
    }

    // Fungsi untuk mendapatkan total jumlah konsumen berdasarkan peran dan cabang
    public function getTotalKonsumen($role, $id_cabang = false)
    {
        $db      = \Config\Database::connect(); // Menghubungkan ke database
        $builder = $db->table($this->table); // Membuat builder untuk tabel konsumen
        $builder->select('konsumen.*'); // Memilih semua kolom dari tabel konsumen

        if ($id_cabang) {
            if ($role == 'cabang') {
                // Jika peran adalah cabang dan id_cabang diberikan, tambahkan kondisi untuk id_cabang
                $builder->where('id_cabang', $id_cabang);
            }
        }

        // Menghitung total hasil berdasarkan kondisi yang diberikan
        return $builder->countAllResults();
    }
}
