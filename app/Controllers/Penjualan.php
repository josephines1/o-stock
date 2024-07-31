<?php

namespace App\Controllers;

use App\Models\CabangModel;
use App\Models\KartuStokModel;
use App\Models\KonsumenModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanPerProduk;
use App\Models\ProdukModel;
use App\Models\SalesmanModel;
use App\Models\UsersModel;
use App\ValidationRules\MyRules;

class Penjualan extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $penjualanModel;
    protected $usersModel;
    protected $cabangModel;
    protected $salesmanModel;
    protected $konsumenModel;
    protected $produkModel;
    protected $penjualanPerProdukModel;
    protected $kartuStokModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->penjualanModel = new PenjualanModel();
        $this->usersModel = new UsersModel();
        $this->cabangModel = new CabangModel();
        $this->konsumenModel = new KonsumenModel();
        $this->salesmanModel = new SalesmanModel();
        $this->produkModel = new ProdukModel();
        $this->penjualanPerProdukModel = new PenjualanPerProduk();
        $this->kartuStokModel = new KartuStokModel();

        /*
        * Mengambil nama kantor untuk diletakkan di header
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];
        $user_login_kantor = $this->cabangModel->find($user_login_kantor_id);
        $this->kantorName = $user_login_kantor['nama'];
    }

    /*
    * Menampilkan data di halaman utama
    */
    public function index(): string
    {
        /*
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kantor dan role digunakan untuk filter data
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];
        $user_login_role = $user_login['role'];

        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_penjualan') ? $this->request->getVar('page_penjualan') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Data yang ditampilkan secara default hanya data pada bulan saat ini
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
            'salesman' => $this->request->getGet('salesman') ? $this->request->getGet('salesman') : 0,
            'produk' => $this->request->getGet('produk') ? $this->request->getGet('produk') : 0,
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun'),
        ];

        /*
        * Menentukan sebutan filter untuk cabang
        */
        if ($filter['cabang'] != 0) {
            $filter_cabang = $this->cabangModel->find($filter['cabang']);
            $filter_cabang_nama = $filter_cabang['nama'];
        } else {
            $filter_cabang_nama = "Semua Cabang";
        }

        /*
        * Menentukan sebutan filter untuk salesman
        */
        if ($filter['salesman'] != 0) {
            $filter_salesman = $this->salesmanModel->find($filter['salesman']);
            $filter_salesman_nama = $filter_salesman['nama'];
        } else {
            $filter_salesman_nama = "Semua Salesman";
        }

        /*
        * Menentukan sebutan filter untuk produk
        */
        if ($filter['produk'] != 0) {
            $filter_produk = $this->produkModel->find($filter['produk']);
            $filter_produk_nama = $filter_produk['nama'];
        } else {
            $filter_produk_nama = "Semua Produk";
        }

        /*
        * Menentukan sebutan filter untuk bulan dan tahun
        */
        if (empty($filter['bulan']) || empty($filter['tahun'])) {
            $filter_waktu = date('Y-m');
        } else {
            $filter_waktu = $filter['tahun'] . '-' . $filter['bulan'];
        }

        /*
        * Jika tidak ada filter bulan dan tahun, maka ambil nilai bulan saat ini
        */
        if (empty($filter['bulan'])) {
            $filter['bulan'] = date('m');
        }

        if (empty($filter['tahun'])) {
            $filter['tahun'] = date('Y');
        }

        /*
        * Mengambil data penjualan yang akan ditampilkan 
        * beserta dengan link pagination
        */
        $penjualanModel = $this->penjualanModel->getPenjualan(false, $filter);
        $pager = $penjualanModel['links'];
        $total = $penjualanModel['total'];
        $perPage = $penjualanModel['perPage'];
        $data_penjualan = $penjualanModel['penjualan'];

        /*
        * Mengambil nilai tahun minimal yang ada pada data untuk 
        * kebutuhan filter tahun
        */
        if ($this->penjualanModel->getMinYear()) {
            $tahun_mulai = $this->penjualanModel->getMinYear();
        } else {
            $tahun_mulai = date('Y');
        }

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_penjualan digunakan untuk menampilkan data penjualan pada tabel
        *
        * data_cabang, data_salesman, dan data_produk digunakan untuk menampilkan pilihan filter cabang
        * tahun_mulai digunakan untuk menampilkan pilihan filter tahun
        *
        * filter_key, filter_cab, filter_pro, filter_bul, dan filter_tah digunakan pada nilai filter 
        * yang sedang diterapkan
        *
        * filter_cabang_nama, filter_salesman_nama, filter_produk_nama, dan filter_waktu digunakan pada sebutan
        * filter yang sedang diterapkan, di dalam tabel
        * 
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'penjualan',
            'title' => 'Penjualan',
            'kantorName' => $this->kantorName,
            'data_penjualan' => $data_penjualan,
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'data_salesman' => $this->salesmanModel->getSalesman(false, $filter, true)['salesman'],
            'data_produk' => $this->produkModel->findAll(),
            'tahun_mulai' => $tahun_mulai,
            'filter_key' => $filter['keyword'],
            'filter_cab' => $filter['cabang'],
            'filter_sal' => $filter['salesman'],
            'filter_pro' => $filter['produk'],
            'filter_bul' => $filter['bulan'],
            'filter_tah' => $filter['tahun'],
            'filter_cabang_nama' => $filter_cabang_nama,
            'filter_salesman_nama' => $filter_salesman_nama,
            'filter_produk_nama' => $filter_produk_nama,
            'filter_waktu' => $filter_waktu,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('penjualan/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchPenjualan()
    {
        /*
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kantor dan role digunakan untuk filter data
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];
        $user_login_role = $user_login['role'];

        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_penjualan') ? $this->request->getVar('page_penjualan') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Data yang ditampilkan secara default hanya data pada bulan saat ini
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
            'salesman' => $this->request->getGet('salesman') ? $this->request->getGet('salesman') : 0,
            'produk' => $this->request->getGet('produk') ? $this->request->getGet('produk') : 0,
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun'),
        ];

        /*
        * Jika ada filter yang terdeteksi null, maka anggap saja tampilkan semua data
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';
        $filter['cabang'] = $filter['cabang'] ? $filter['cabang'] : 0;
        $filter['salesman'] = $filter['salesman'] ? $filter['salesman'] : 0;

        /*
        * Menentukan sebutan filter untuk cabang
        */
        if ($filter['cabang'] != 0) {
            $filter_cabang = $this->cabangModel->find($filter['cabang']);
            $filter_cabang_nama = $filter_cabang['nama'];
        } else {
            $filter_cabang_nama = "Semua Cabang";
        }

        /*
        * Menentukan sebutan filter untuk salesman
        */
        if ($filter['salesman'] != 0) {
            $filter_salesman = $this->salesmanModel->find($filter['salesman']);
            $filter_salesman_nama = $filter_salesman['nama'];
        } else {
            $filter_salesman_nama = "Semua Salesman";
        }

        /*
        * Menentukan sebutan filter untuk produk
        */
        if ($filter['produk'] != 0) {
            $filter_produk = $this->produkModel->find($filter['produk']);
            $filter_produk_nama = $filter_produk['nama'];
        } else {
            $filter_produk_nama = "Semua Produk";
        }

        /*
        * Menentukan sebutan filter untuk bulan dan tahun
        */
        if (empty($filter['bulan']) || empty($filter['tahun'])) {
            $filter_waktu = date('Y-m');
        } else {
            $filter_waktu = $filter['tahun'] . '-' . $filter['bulan'];
        }

        /*
        * Jika tidak ada filter bulan dan tahun, maka ambil nilai bulan saat ini
        */
        if (empty($filter['bulan'])) {
            $filter['bulan'] = date('m');
        }

        if (empty($filter['tahun'])) {
            $filter['tahun'] = date('Y');
        }

        /*
        * Mengambil hasil pencarian yang akan ditampilkan
        * beserta dengan link pagination
        */
        $penjualanModel = $this->penjualanModel->getPenjualan(false, $filter);
        $pager = $penjualanModel['links'];
        $total = $penjualanModel['total'];
        $perPage = $penjualanModel['perPage'];
        $data_penjualan = $penjualanModel['penjualan'];

        $data_salesman = $this->salesmanModel->findAll();
        $data_cabang = $this->cabangModel->findAll();

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * kantorName digunakan pada user title di header
        *
        * data_penjualan digunakan untuk menampilkan data penjualan pada tabel
        *
        * filter_cabang_nama, filter_salesman_nama, filter_produk_nama, filter_waktu, dan filter_keyword
        * digunakan pada sebutan filter yang sedang diterapkan, di dalam tabel
        * 
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_penjualan' => $data_penjualan,
            'cabang' => $data_cabang,
            'salesman' => $data_salesman,
            'filter_cabang_nama' => $filter_cabang_nama,
            'filter_salesman_nama' => $filter_salesman_nama,
            'filter_produk_nama' => $filter_produk_nama,
            'filter_waktu' => $filter_waktu,
            'filter_keyword' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('penjualan/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data baru
    */
    public function add()
    {
        /*
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kantor dan role digunakan untuk menentukan permission
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];
        $user_login_role = $user_login['role'];

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Data yang ditampilkan secara default hanya data pada bulan saat ini
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
        ];

        /*
        * Mengirim data-data yang diperlukan pada add konsumen
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * data_salesman digunakan pada opsi select salesman
        * data_konsumen digunakan pada opsi select konsumen
        * data_produk digunakan pada opsi select produk
        */
        $data = [
            'group' => 'penjualan',
            'title' => 'Tambah Data Penjualan',
            'kantorName' => $this->kantorName,
            'data_salesman' => $this->salesmanModel->getSalesman(false, $filter)['salesman'],
            'data_konsumen' => $this->konsumenModel->findAll(),
            'data_produk' => $this->produkModel->findAll(),
        ];

        return view('penjualan/tambah', $data);
    }

    /*
    * Menyimpan data baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add mutasi
        */
        $rules = [
            'konsumen' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Nama konsumen harus diisi',
                    'numeric' => 'Pilih konsumen yang tersedia',
                ]
            ],
            'salesman' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Salesman penjualan harus diisi',
                    'numeric' => 'Pilih salesman yang tersedia',
                ]
            ],
            'produk.*' => [
                'rules' => [
                    'required',
                    'numeric',
                ],
                'errors' => [
                    'required' => 'Produk harus diisi',
                    'numeric' => 'Pilih produk yang tersedia',
                ]
            ],
            'jumlah.*' => [
                'rules' => [
                    'required',
                    'regex_match[/^[0-9]+$/]',
                    'required_with[produk]',
                    'not_in_list[0]'
                ],
                'errors' => [
                    'required' => 'Jumlah produk harus diisi',
                    'regex_match' => 'Jumlah harus berupa angka',
                    'not_in_list' => 'Angka tidak boleh 0'
                ]
            ],
            'disc.*' => [
                'rules' => 'regex_match[/^[0-9]+(\.[0-9]+)?$/]',
                'errors' => [
                    'regex_match' => 'Disc harus berupa angka',
                ]
            ]
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add penjualan 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('penjualan/new'))->withInput();
        }

        /*
        * Melakukan Validasi Lanjutan untuk produk dan jumlahnya
        * 
        * 1. Ambil inputnya 
        * 2. Validasi dengan function unique_value dan check_stock di app\ValidationRules\MyRules.php
        * 3. Jika tidak valid, kembali ke halaman add penjualan dan tampilkan error pada field yang tidak valid
        */
        $input = $this->request->getPost();
        $productErrors = MyRules::unique_value($input);
        $stockErrors = MyRules::check_stock($input);
        if (!empty($productErrors) || !empty($stockErrors)) {
            if (!empty($productErrors)) {
                foreach ($productErrors as $index => $message) {
                    $this->validator->setError("produk.$index", $message);
                }
            }
            if (!empty($stockErrors)) {
                foreach ($stockErrors as $index => $message) {
                    // Set error khusus untuk field jumlah berdasarkan indeks
                    $this->validator->setError("jumlah.$index", $message);
                }
            }
            return redirect()->to(base_url('penjualan/new'))->withInput();
        }

        /*
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kode cabang dibutuhkan sebagai kode cabang asal
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];
        $kode_cabang = $user_login['kode_kantor'];

        /*
        * Membuat kode mutasi baru
        */
        $lastInvoice = $this->penjualanModel->getLastInvoiceNumber($kode_cabang);
        if (!$lastInvoice) {
            // Jika tidak ada nomor invoice untuk bulan ini, mulai dari 001
            $invoiceNumber = 'INV/' . $kode_cabang . '/' . date('Y/m') . '/001';
        } else {
            // Jika sudah ada nomor invoice untuk bulan ini, tambahkan 1
            $lastInvoiceNumber = $lastInvoice['no_invoice'];
            $lastInvoiceNumber = intval(substr($lastInvoiceNumber, -3));
            $incrementedNumber = str_pad($lastInvoiceNumber + 1, 3, '0', STR_PAD_LEFT);;
            $invoiceNumber = 'INV/' . $kode_cabang . '/' . date('Y/m') . '/' . $incrementedNumber;
        }

        /*
        * Simpan data ke tabel penjualan
        */
        $this->penjualanModel->save([
            'no_invoice' => $invoiceNumber,
            'tanggal' => date('Y-m-d'),
            'id_konsumen' => $this->request->getPost('konsumen'),
            'id_salesman' => $this->request->getPost('salesman'),
            'id_cabang' => $user_login_kantor_id,
        ]);

        /*
        * Ambil id data mutasi yang baru tersimpan
        */
        $id_penjualan = $this->penjualanModel->insertID();

        /*
        * Ambil nilai input produk, jumlah, dan discount nya
        */
        $produkValues = $this->request->getPost('produk');
        $jumlahValues = $this->request->getPost('jumlah');
        $discValues = $this->request->getPost('disc');

        /*
        * Simpan setiap produk ke database
        */
        foreach ($produkValues as $index => $p) {
            $harga_produk = $this->produkModel->find($p)['harga_jual'];

            /*
            * Simpan ke tabel penjualan_per_produk
            */
            $this->penjualanPerProdukModel->save([
                'id_penjualan' => $id_penjualan,
                'id_produk' => $p,
                'jumlah' => $jumlahValues[$index],
                'discount' => $discValues[$index],
                'harga' => $harga_produk,
            ]);

            /*
            * Simpan ke tabel kartu stok sebagai stok keluar
            */
            $this->kartuStokModel->save([
                'tanggal' => date('Y-m-d'),
                'no_bukti' => $invoiceNumber,
                'keterangan' => 'Penjualan',
                'id_produk' => $p,
                'id_cabang' => $user_login_kantor_id,
                'stok_masuk' => 0,
                'stok_keluar' => $jumlahValues[$index],
            ]);
        }

        /*
        * Atur pesan berhasil dan kembali ke halaman index penjualan
        */
        session()->setFlashdata('berhasil', 'Data penjualan berhasil ditambahkan');
        return redirect()->to('/penjualan');
    }

    /*
    * Menampilkan invoice penjualan
    */
    public function cetakInvoice($id)
    {
        // Mengambil Data Penjualan
        $invoice_db = $this->penjualanModel->getPenjualan($id)['penjualan'];

        // Jika data tidak ditemukan, tampilkan 404 NOT FOUND
        if (empty($invoice_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Invoice Tidak Ditemukan');
        }

        // Mengambil Data Produk Penjualan
        $invoice_produks = $this->penjualanPerProdukModel->where('id_penjualan', $id)->findAll();

        // Total Data Produk
        $totalData = count($invoice_produks);

        // Mengatur jumlah data per halaman
        $perPage = 5;

        // Menghitung total halaman
        $totalPages = ceil($totalData / $perPage);

        // Inisialisasi variabel data (untuk produk)
        $data = [];

        // Looping untuk mendapatkan data produk per halaman
        for ($page = 1; $page <= $totalPages; $page++) {
            $offset = ($page - 1) * $perPage;
            $data[$page] = [
                'page' => $page,
                'data' => $this->penjualanPerProdukModel->getPenjualanPerProduk($id, $perPage, $offset),
            ];
        }

        // Hitung total harga dan masukkan ke dalam array produk
        foreach ($data as $p) {
            $produk = $p['data'][0];

            // Hitung harga setelah diskon
            $harga_setelah_diskon = $produk['harga'] - ($produk['harga'] * ($produk['discount'] / 100));

            // Hitung total harga
            $total_harga = $harga_setelah_diskon * $produk['jumlah'];

            // Format total harga dengan menggunakan number_format
            $produk['total_harga_formatted'] = number_format($total_harga, 0, ',', '.');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman detail penjualan
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db untuk menampilkan informasi mengenai penjualan terkait
        * produks untuk menampilkan setiap produk dan detail harga dan discount nya
        *
        * total_pages dan perPage digunakan untuk pagination invoice
        */
        $data = [
            'group' => 'penjualan',
            'title' => 'Cetak Invoice',
            'kantorName' => $this->kantorName,
            'db' => $invoice_db['0'],
            'produks' => $data,
            'total_pages' => $totalPages,
            'perPage' => $perPage
        ];

        return view('penjualan/cetak-invoice', $data);
    }
}
