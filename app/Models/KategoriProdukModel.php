<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriProdukModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table = 'kategori_produk';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['nama', 'slug'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    // Fungsi untuk mendapatkan data kategori produk dengan opsi filter, slug, dan paginasi
    public function getKategori($slug = false, $filter = false, $perPage = 10)
    {
        $pager = service('pager'); // Mengambil layanan pager
        $pager->setPath('kategori', 'kategori'); // Menetapkan path untuk pager

        // Mengambil halaman saat ini dari parameter GET atau default ke halaman 1
        $page = (@$_GET['page_kategori']) ? $_GET['page_kategori'] : 1;
        $offset = ($page - 1) * $perPage; // Menghitung offset untuk paginasi

        $db      = \Config\Database::connect(); // Menghubungkan ke database
        $builder = $db->table($this->table); // Membuat builder untuk tabel kategori_produk
        $builder->select('kategori_produk.*'); // Memilih semua kolom dari tabel kategori_produk
        $builder->orderBy('nama', 'ASC'); // Mengurutkan hasil berdasarkan nama dalam urutan ASC

        $total = 0; // Variabel untuk menyimpan total hasil

        // Menghitung total hasil berdasarkan query
        $countQuery = clone $builder; // Mengkloning builder untuk menghitung total
        $total = $countQuery->countAllResults(); // Menghitung total hasil

        if ($slug) {
            // Jika slug disediakan, hitung total berdasarkan slug
            $countQuery = clone $builder; // Mengkloning builder untuk menghitung total berdasarkan slug
            $total = $countQuery->where('slug', $slug)->countAllResults(); // Menghitung total hasil untuk slug tertentu
        } else if ($filter) {
            // Jika filter disediakan, tambahkan filter ke query
            $filter_keyword = $filter['keyword'];

            if ($filter_keyword) {
                // Jika ada keyword filter, tambahkan kondisi LIKE pada nama
                $builder->groupStart()
                    ->like('nama', $filter_keyword)
                    ->groupEnd();
            }

            // Menghitung total hasil berdasarkan filter
            $countQuery = clone $builder; // Mengkloning builder untuk menghitung total berdasarkan filter
            $total = $countQuery->countAllResults(); // Menghitung total hasil dengan filter
        }

        if ($slug) {
            // Jika slug disediakan, ambil hasil untuk slug tertentu dengan paginasi
            $result = $builder->where('slug', $slug)->get($perPage, $offset)->getResultArray(); // Mengambil hasil untuk slug tertentu
        } else {
            // Jika tidak ada slug, ambil hasil dengan paginasi
            $result = $builder->get($perPage, $offset)->getResultArray(); // Mengambil hasil dengan paginasi
        }

        // Menghasilkan data kategori, link paginasi, total hasil, per halaman, dan halaman saat ini
        return [
            'kategori' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'kategori'), // Membuat link paginasi
            'total' => $total, // Total hasil
            'perPage' => $perPage, // Jumlah hasil per halaman
            'page' => $page, // Halaman saat ini
        ];
    }
}
