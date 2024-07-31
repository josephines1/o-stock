<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    // Nama tabel di database
    protected $table      = 'penjualan';

    // Nama primary key
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = ['no_invoice', 'id_cabang', 'id_konsumen', 'id_salesman', 'tanggal'];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    /**
     * Mendapatkan data penjualan dengan filter dan paginasi
     *
     * @param string|false $id Jika disediakan, hanya data dengan id ini yang akan diambil
     * @param array|false $filter Opsi filter yang dapat diterapkan pada query
     * @param int $perPage Jumlah item per halaman untuk pagination
     * @return array Daftar penjualan dan informasi pagination
     */
    public function getPenjualan($id = false, $filter = false, $perPage = 10)
    {
        // Mengambil layanan pager untuk pagination
        $pager = service('pager');
        $pager->setPath('penjualan', 'penjualan'); // Menetapkan path untuk paginasi

        // Mengambil halaman saat ini dari query parameter atau default ke 1
        $page = (@$_GET['page_penjualan']) ? $_GET['page_penjualan'] : 1;
        $offset = ($page - 1) * $perPage; // Menghitung offset untuk paginasi

        // Menghubungkan ke database dan memulai query builder
        $db = \Config\Database::connect();
        $builder = $db->table($this->table); // Membuat query builder untuk tabel penjualan
        $builder->select('penjualan.*, kantor.nama as nama_cabang, kantor.alamat as alamat_cabang, konsumen.nama as nama_konsumen, konsumen.alamat as alamat_konsumen, konsumen.kota as kota_konsumen, konsumen.no_telp as no_telp_konsumen, salesman.nama as nama_salesman');
        $builder->join('kantor', 'kantor.id = penjualan.id_cabang'); // Bergabung dengan tabel kantor
        $builder->join('konsumen', 'konsumen.id = penjualan.id_konsumen'); // Bergabung dengan tabel konsumen
        $builder->join('salesman', 'salesman.id = penjualan.id_salesman'); // Bergabung dengan tabel salesman
        $builder->join('penjualan_per_produk', 'penjualan_per_produk.id_penjualan = penjualan.id'); // Bergabung dengan tabel penjualan_per_produk
        $builder->orderBy('tanggal', 'DESC'); // Mengurutkan hasil berdasarkan tanggal
        $builder->orderBy('created_at', 'DESC'); // Mengurutkan hasil berdasarkan created_at

        $total = 0;

        if ($id) {
            $builder->where('penjualan.id', $id); // Jika id diberikan, tambahkan kondisi untuk id penjualan
        } else if ($filter) {
            $filter_keyword = $filter['keyword'];
            $filter_cabang = $filter['cabang'];
            $filter_salesman = $filter['salesman'];
            $filter_bulan = $filter['bulan'];
            $filter_tahun = $filter['tahun'];
            $filter_produk = $filter['produk'];

            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('penjualan.no_invoice', $filter_keyword)
                    ->orLike('konsumen.nama', $filter_keyword)
                    ->groupEnd();
            }

            if ($filter_cabang != 0) {
                $builder->where('penjualan.id_cabang', $filter_cabang);
            }

            if ($filter_salesman != 0) {
                $builder->where('penjualan.id_salesman', $filter_salesman);
            }

            if ($filter_produk != 0) {
                $builder->where('penjualan_per_produk.id_produk', $filter_produk);
            }

            if ($filter_bulan !== null && $filter_tahun !== null) {
                $waktu_filter = $filter_tahun . '-' . $filter_bulan;
                $builder->where('DATE_FORMAT(penjualan.tanggal, "%Y-%m")', $waktu_filter);
            }

            if ($filter_bulan === null && $filter_tahun === null) {
                $waktu_sekarang = date('Y-m');
                $builder->where('DATE_FORMAT(penjualan.tanggal, "%Y-%m")', $waktu_sekarang);
            }
        }

        $builder->groupBy('penjualan.id'); // Mengelompokkan hasil berdasarkan id penjualan untuk mencegah entri duplikat

        // Menghitung jumlah total hasil
        $countQuery = clone $builder;
        $total = $countQuery->countAllResults(false); // Hitung total tanpa mereset builder

        if ($id) {
            $result = $builder->get($perPage, $offset)->getResultArray(); // Jika id diberikan, ambil hasil dengan paginasi
        } else {
            $result = $builder->get($perPage, $offset)->getResultArray(); // Ambil hasil dengan paginasi
        }

        return [
            'penjualan' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'penjualan'), // Membuat link untuk paginasi
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    // Mendapatkan nomor invoice terakhir berdasarkan kode cabang
    public function getLastInvoiceNumber($kode_cabang)
    {
        return $this->db->table($this->table)
            ->select('no_invoice')
            ->like('no_invoice', 'INV/' . $kode_cabang . '/' . date('Y/m'), 'after')
            ->orderBy('no_invoice', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    // Menghitung total penjualan berdasarkan role dan id_cabang
    public function getTotalPenjualan($role, $id_cabang = false)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('penjualan.*');

        if ($id_cabang) {
            if ($role == 'cabang') {
                $builder->where('id_cabang', $id_cabang);
            }
        }

        return $builder->countAllResults();
    }

    // Mendapatkan tahun minimum penjualan dari database
    public function getMinYear()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->selectMin('YEAR(tanggal)', 'min_year');

        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->min_year : null;
    }

    // Mengambil jumlah penjualan selama 7 hari terakhir
    public function getPenjualanLastSevenDays($role, $id_cabang = false)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $today = date("Y-m-d"); // Mendapatkan tanggal hari ini
        $seven_days_ago = date("Y-m-d", strtotime("-7 days")); // Mendapatkan tanggal 7 hari kebelakang

        // Query untuk menghitung jumlah transaksi per hari selama 7 hari terakhir
        $builder->select('DATE(tanggal) as tanggal, COUNT(*) as jumlah_transaksi')
            ->where('tanggal >=', $seven_days_ago)
            ->where('tanggal <=', $today)
            ->groupBy('DATE(tanggal)');

        $builder->orderBy('tanggal', 'ASC');

        if ($id_cabang) {
            if ($role == 'cabang') {
                $builder->where('id_cabang', $id_cabang);
            }
        }

        $query = $builder->get();

        // Menyusun array dengan 7 elemen untuk setiap hari dalam 7 hari terakhir
        $transaksiEachSevenDays = array_fill(0, 7, 0);

        foreach ($query->getResultArray() as $data) {
            $dateIndex = (int) (strtotime($data['tanggal']) - strtotime("-7 days")) / (60 * 60 * 24);
            if ($dateIndex >= 0 && $dateIndex < 7) {
                $transaksiEachSevenDays[$dateIndex] = $data['jumlah_transaksi'];
            }
        }

        return $transaksiEachSevenDays;
    }

    // Mengambil jumlah penjualan selama 12 bulan terakhir
    public function getPenjualanLastTwelveMonths($role, $id_cabang = false)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $today = date("Y-m-d"); // Mendapatkan tanggal hari ini
        $twelve_months_ago = date("Y-m-d", strtotime("-12 months")); // Mendapatkan tanggal 12 bulan kebelakang

        // Query untuk menghitung jumlah transaksi per bulan selama 12 bulan terakhir
        $builder->select('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, COUNT(*) as jumlah_transaksi')
            ->where('tanggal >=', $twelve_months_ago)
            ->where('tanggal <=', $today)
            ->groupBy('YEAR(tanggal), MONTH(tanggal)')
            ->orderBy('tahun', 'ASC');

        $builder->orderBy('bulan', 'ASC');

        if ($id_cabang) {
            if ($role == 'cabang') {
                $builder->where('id_cabang', $id_cabang);
            }
        }

        $query = $builder->get();

        // Menyusun array dengan 12 elemen untuk setiap bulan dalam 12 bulan terakhir
        $transaksiPerBulan = array_fill(0, 12, 0);
        $currentMonth = (int) date("n");
        $currentYear = (int) date("Y");

        foreach ($query->getResultArray() as $data) {
            $tahun = (int) $data['tahun'];
            $bulan = (int) $data['bulan'];
            $jumlah_transaksi = (int) $data['jumlah_transaksi'];

            $monthDiff = (($currentYear - $tahun) * 12) + ($currentMonth - $bulan);

            if ($monthDiff >= 0 && $monthDiff < 12) {
                $transaksiPerBulan[11 - $monthDiff] = $jumlah_transaksi;
            }
        }

        return $transaksiPerBulan;
    }
}
