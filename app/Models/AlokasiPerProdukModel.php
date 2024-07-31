<?php

namespace App\Models;

use CodeIgniter\Model;

class alokasiPerProdukModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table = 'alokasi_per_produk';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['id_alokasi', 'id_produk', 'jumlah'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    /*
    * Mengambil data alokasi per produk berdasarkan id_alokasi
    */
    public function getAlokasiPerProduk($id_alokasi)
    {
        // Menghubungkan ke database
        $db = \Config\Database::connect();

        // Membuat query builder untuk tabel alokasi_per_produk
        $builder = $db->table($this->table);

        // Menentukan kolom yang akan dipilih dan melakukan join dengan tabel produk
        $builder->select('alokasi_per_produk.*, produk.nama as nama_produk, produk.kode_produk');
        $builder->join('produk', 'produk.id = alokasi_per_produk.id_produk');
        $builder->where('id_alokasi', $id_alokasi);

        // Mendapatkan hasil query dalam bentuk array
        $result = $builder->get()->getResultArray();

        // Mengembalikan hasil query
        return $result;
    }

    /*
    * Menemukan data alokasi per produk berdasarkan id_alokasiPerProduk
    */
    public function findProdukAlokasi($id_alokasiPerProduk)
    {
        // Menghubungkan ke database
        $db = \Config\Database::connect();

        // Membuat query builder untuk tabel alokasi_per_produk
        $builder = $db->table($this->table);

        // Menentukan kolom yang akan dipilih dan melakukan join dengan tabel produk
        $builder->select('alokasi_per_produk.*, produk.nama as nama_produk, produk.kode_produk');
        $builder->join('produk', 'produk.id = alokasi_per_produk.id_produk');
        $builder->where('alokasi_per_produk.id', $id_alokasiPerProduk);

        // Mendapatkan hasil query dalam bentuk array
        $result = $builder->get()->getResultArray();

        // Mengembalikan hasil query
        return $result;
    }

    /*
    * Menemukan data kartu stok berdasarkan id_cabang, id_produk, dan ca_alokasiPerProduk
    * terkait produk alokasi yang tercatat
    */
    public function findKartuStok($id_cabang, $id_produk, $ca_alokasiPerProduk)
    {
        // Menghubungkan ke database
        $db = \Config\Database::connect();

        // Membuat query builder untuk tabel kartu_stok
        $builder = $db->table('kartu_stok');

        // Menentukan kolom yang akan dipilih dan melakukan join dengan tabel alokasi_per_produk
        $builder->select('kartu_stok.*');
        $builder->join('alokasi_per_produk', 'alokasi_per_produk.id_produk = kartu_stok.id_produk');
        $builder->where('kartu_stok.id_cabang', $id_cabang);
        $builder->where('kartu_stok.id_produk', $id_produk);
        $builder->where('kartu_stok.created_at', $ca_alokasiPerProduk);

        // Mendapatkan hasil query dalam bentuk array
        $result = $builder->get()->getResultArray();

        // Mengembalikan hasil query
        return $result;
    }
}
