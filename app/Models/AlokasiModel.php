<?php

namespace App\Models;

use CodeIgniter\Model;

class AlokasiModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table      = 'alokasi';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['tanggal', 'id_cabang', 'no_alokasi'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    /*
    * Mengambil data alokasi berdasarkan id, filter
    */
    public function getAlokasi($id = false, $filter = false, $perPage = 10)
    {
        // Mendapatkan layanan pager dari CodeIgniter 4
        $pager = service('pager');

        // Menetapkan path dasar untuk pager
        $pager->setPath('alokasi', 'alokasi');

        // Mendapatkan halaman saat ini dari query string atau default ke 1
        $page = (@$_GET['page_alokasi']) ? $_GET['page_alokasi'] : 1;

        // Menghitung offset berdasarkan halaman saat ini dan jumlah item per halaman
        $offset = ($page - 1) * $perPage;

        // Menghubungkan ke database dan mendapatkan builder untuk tabel alokasi
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);

        // Menentukan kolom yang akan dipilih dan melakukan join dengan tabel kantor
        $builder->select('alokasi.*, kantor.nama as nama_cabang');
        $builder->join('kantor', 'kantor.id = alokasi.id_cabang');
        $builder->join('alokasi_per_produk', 'alokasi_per_produk.id_alokasi = alokasi.id');

        // Mengurutkan hasil berdasarkan tanggal secara descending
        $builder->orderBy('tanggal', 'DESC');

        // Inisialisasi total hasil
        $total = 0;

        // Membuat clone dari builder untuk menghitung total hasil
        $countQuery = clone $builder;

        // Menghitung total hasil tanpa filter
        $total = $countQuery->countAllResults();

        // Mendapatkan waktu sekarang dalam format 'Y-m'
        $waktu_sekarang = date('Y-m');

        // Jika id diberikan, menghitung total hasil berdasarkan id
        if ($id) {
            $countQuery = clone $builder;
            $total = $countQuery->where('alokasi.id', $id)->countAllResults();
        } else if ($filter) {
            // Jika filter diberikan, menerapkan filter pada query
            $filter_keyword = $filter['keyword'];
            $filter_cabang = $filter['cabang'];
            $filter_produk = $filter['produk'];
            $filter_bulan = $filter['bulan'];
            $filter_tahun = $filter['tahun'];

            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('alokasi.no_alokasi', $filter_keyword)
                    ->groupEnd();
            }

            if ($filter_cabang != 0) {
                $builder->groupStart()
                    ->where('alokasi.id_cabang', $filter_cabang)
                    ->groupEnd();
            }

            if ($filter_produk != 0) {
                $builder->groupStart()
                    ->where('alokasi_per_produk.id_produk', $filter_produk)
                    ->groupEnd();
            }

            if ($filter_bulan !== null && $filter_tahun !== null) {
                $waktu_filter = $filter_tahun . '-' . $filter_bulan;
                $builder->groupStart()
                    ->where('DATE_FORMAT(alokasi.tanggal, "%Y-%m")', $waktu_filter)
                    ->groupEnd();
            }

            if ($filter_bulan === null && $filter_tahun === null) {
                $builder->groupStart()
                    ->where('DATE_FORMAT(alokasi.tanggal, "%Y-%m")', $waktu_sekarang)
                    ->groupEnd();
            }

            $countQuery = clone $builder;
            $total = $countQuery->countAllResults();
        }

        // Menghindari duplikasi tampilan data
        $builder->groupBy('alokasi.id');

        if ($id) {
            // Jika id diberikan, mendapatkan hasil berdasarkan id dengan paging
            $result = $builder->where('alokasi.id', $id)->get($perPage, $offset)->getResultArray();
        } else {
            // Mendapatkan hasil dengan paging
            $result = $builder->get($perPage, $offset)->getResultArray();
        }

        // Mengembalikan array yang berisi hasil alokasi, link pager, total hasil, jumlah item per halaman, 
        // dan halaman saat ini
        // Syntax: $pager->makeLinks($page, $perPage, $total, 'template_name', $segment, 'my-group');
        return [
            'alokasi' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'alokasi'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    // Mengambil nomor alokasi terakhir berdasarkan kode cabang
    public function getLastAlokasiNumber($kode_cabang)
    {
        return $this->db->table($this->table)
            ->select('no_alokasi')
            ->like('no_alokasi', 'ALK/' . $kode_cabang . '/' . date('Y/m'), 'after')
            ->orderBy('no_alokasi', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    /*
    * Mendapatkan tahun minimum dari tabel alokasi
    */
    public function getMinYear()
    {
        // Menghubungkan ke database dan mendapatkan builder untuk tabel alokasi
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);

        // Memilih tahun minimum dari kolom tanggal
        $builder->selectMin('YEAR(tanggal)', 'min_year');

        // Menjalankan query dan mendapatkan hasil
        $query = $builder->get();
        $result = $query->getRow();

        // Mengembalikan tahun minimum atau null jika tidak ada hasil
        return $result ? $result->min_year : null;
    }

    /*
    * Menemukan data kartu stok berdasarkan id cabang dan tanggal alokasi
    */
    public function findKartuStok($id_cabang, $ca_alokasi)
    {
        // Menghubungkan ke database
        $db      = \Config\Database::connect();

        // Mendapatkan builder untuk tabel kartu_stok
        $builder = $db->table('kartu_stok');

        // Memilih semua kolom dari tabel kartu_stok
        $builder->select('kartu_stok.*');

        // Melakukan join dengan tabel alokasi berdasarkan id_cabang
        $builder->join('alokasi', 'alokasi.id_cabang = kartu_stok.id_cabang');

        // Menerapkan filter berdasarkan id_cabang dan tanggal alokasi
        $builder->where('kartu_stok.id_cabang', $id_cabang);
        $builder->where('kartu_stok.no_bukti', '-');
        $builder->where('kartu_stok.created_at', $ca_alokasi);

        // Menjalankan query dan mendapatkan hasil sebagai array
        $result = $builder->get()->getResultArray();

        // Mengembalikan hasil
        return $result;
    }
}
