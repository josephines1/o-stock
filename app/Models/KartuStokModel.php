<?php

namespace App\Models;

use CodeIgniter\Model;

class KartuStokModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table = 'kartu_stok';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['no_bukti', 'keterangan', 'tanggal', 'id_produk', 'id_cabang', 'stok_masuk', 'stok_keluar'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    // Fungsi untuk mendapatkan data kartu stok berdasarkan filter dan ID
    public function getKartuStok($id = false, $filter = false, $perPage = 10)
    {
        // Mengambil layanan pager untuk pagination
        $pager = service('pager');
        $pager->setPath('kartu_stok', 'kartu_stok');

        // Mengambil halaman saat ini dari query parameter atau default ke 1
        $page = (@$_GET['page_kartu_stok']) ? $_GET['page_kartu_stok'] : 1;
        $offset = ($page - 1) * $perPage;

        $waktu_sekarang = date('Y-m'); // Waktu sekarang dalam format Tahun-Bulan
        $db = \Config\Database::connect(); // Menghubungkan ke database

        // Query utama untuk mendapatkan kartu stok
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('
            kartu_stok.*,
            produk.nama as nama_produk, 
            produk.kode_produk,
            kantor.nama as nama_cabang,
            supplier.nama as nama_supplier,
            SUM(stok_masuk) - SUM(stok_keluar) AS stok_akhir
        ');
        $builder->join('produk', 'produk.id = kartu_stok.id_produk');
        $builder->join('kantor', 'kantor.id = kartu_stok.id_cabang');
        $builder->join('supplier', 'supplier.id = produk.id_supplier');
        $builder->groupBy('id_produk, id_cabang');

        // Menginisialisasi variabel total untuk menghitung total data
        $total = 0;

        // Menghitung total data tanpa filter
        $countQuery = clone $builder;
        $total = $countQuery->countAllResults();

        // Menambahkan filter jika ada
        if ($filter) {
            $filter_cabang = $filter['cabang'];
            $filter_produk = $filter['produk'];
            $filter_supplier = $filter['supplier'];

            if ($filter_cabang != 0) {
                $builder->groupStart()
                    ->where('kartu_stok.id_cabang', $filter_cabang)
                    ->groupEnd();
            }

            if ($filter_produk != 0) {
                $builder->groupStart()
                    ->where('kartu_stok.id_produk', $filter_produk)
                    ->groupEnd();
            }

            if ($filter_supplier != 0) {
                $builder->groupStart()
                    ->where('produk.id_supplier', $filter_supplier)
                    ->groupEnd();
            }
        }

        // Mengambil hasil dari query dan menggabungkan hasil stok terakhir dengan hasil builder
        if ($id) {
            $result = $builder->where('kartu_stok.id', $id)->get()->getResultArray();
        } else {
            $result = $builder->get()->getResultArray();
        }

        // Mengembalikan array berisi data salesman, informasi pagination, dan total data
        return [
            'kartu_stok' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'kartu_stok'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    // Fungsi untuk mendapatkan produk yang tidak memiliki stok awal di cabang tertentu
    public function getProdukNoStokAwal($id_cabang)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('produk');
        $builder->select('
            produk.id, 
            produk.nama as nama_produk, 
            produk.kode_produk
        ');
        $builder->join('kartu_stok', 'kartu_stok.id_produk = produk.id AND kartu_stok.id_cabang = ' . $id_cabang, 'left');
        $builder->where('kartu_stok.id IS NULL');
        $builder->groupBy('produk.id');

        $results = $builder->get()->getResultArray();

        return $results;
    }

    // Fungsi untuk mendapatkan stok akhir terbaru berdasarkan produk dan tanggal
    public function getLatestStokAkhir($id_produk, $tanggal)
    {
        $usersModel = new UsersModel(); // Mengambil model UsersModel
        $username_login = user()->username; // Mengambil username login
        $user_login = $usersModel->getUsers($username_login)['users'][0]; // Mengambil data pengguna
        $id_cabang = $user_login['kantor_id']; // Mengambil ID cabang pengguna

        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('
            kartu_stok.*,
            (SELECT SUM(ks1.stok_masuk - ks1.stok_keluar) 
                            FROM kartu_stok ks1 
                            WHERE ks1.id_produk = kartu_stok.id_produk 
                            AND ks1.id_cabang = kartu_stok.id_cabang 
                            AND ks1.created_at <= kartu_stok.created_at) as stok_akhir
        ');
        $builder->where('kartu_stok.id_produk', $id_produk);
        $builder->where('kartu_stok.id_cabang', $id_cabang);
        $builder->where('kartu_stok.tanggal <=', $tanggal);
        $builder->orderBy('kartu_stok.created_at', 'DESC');

        $result = $builder->get()->getRowArray();

        $result['stok_akhir'] = $result == null ? null : $result['stok_akhir'];

        return $result['stok_akhir'];
    }
}
