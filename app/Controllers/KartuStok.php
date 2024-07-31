<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;
use App\Models\ProdukModel;
use App\Models\SupplierModel;
use App\Models\KartuStokModel;

class KartuStok extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan di halaman Cabang
    */
    protected $kartuStokModel;
    protected $produkModel;
    protected $cabangModel;
    protected $usersModel;
    protected $supplierModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan di halaman Cabang
        */
        $this->kartuStokModel = new KartuStokModel();
        $this->produkModel = new ProdukModel();
        $this->cabangModel = new CabangModel();
        $this->usersModel = new UsersModel();
        $this->supplierModel = new SupplierModel();

        /*
        * Mengambil nama kantor untuk diletakkan di header
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];
        $user_login_kantor = $this->cabangModel->find($user_login_kantor_id);
        $this->kantorName = $user_login_kantor['nama'];
    }

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
        $currentPage = $this->request->getVar('page_kartu-stok') ? $this->request->getVar('page_kartu-stok') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        * Data yang ditampilkan secara default hanya data pada bulan saat ini
        */
        $filter = [
            'supplier' => $this->request->getGet('supplier') ? $this->request->getGet('supplier') : 0,
            'produk' => $this->request->getGet('produk') ? $this->request->getGet('produk') : 0,
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
        ];

        /*
        * Menentukan sebutan filter untuk supplier
        */
        if ($filter['supplier'] != 0) {
            $filter_supplier = $this->supplierModel->find($filter['supplier']);
            $filter_supplier_nama = $filter_supplier['nama'];
        } else {
            $filter_supplier_nama = "Semua Supplier";
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
        * Menentukan sebutan filter untuk cabang
        */
        if ($filter['cabang'] != 0) {
            $filter_cabang = $this->cabangModel->find($filter['cabang']);
            $filter_cabang_nama = $filter_cabang['nama'];
        } else {
            $filter_cabang_nama = "Semua Cabang";
        }

        /*
        * Mengambil data kartu stok yang akan ditampilkan 
        * beserta dengan link pagination
        */
        $kartuStokModel = $this->kartuStokModel->getKartuStok(false, $filter);
        $pager = $kartuStokModel['links'];
        $total = $kartuStokModel['total'];
        $perPage = $kartuStokModel['perPage'];
        $data_kartu_stok = $kartuStokModel['kartu_stok'];

        /*
        * Mengirim data-data yang diperlukan pada view index kartu stok
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_kartu_stok digunakan untuk menampilkan data alokasi pada tabel
        *
        * data_cabang, data_produk, data_supplier, dan tahun_mulai digunakan
        * untuk kebutuhan filter
        *
        * filter_sup, filter_pro, filter_cab, filter_bul dan filter_tah 
        * digunakan pada nilai select filter yang sedang diterapkan
        *
        * filter_supplier, filter_produk, filter_cabang, dan filter_waktu
        * digunakan pada string nama filter yang sedang diterapkan di tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'kartu_stok',
            'title' => 'Kartu Stok',
            'kantorName' => $this->kantorName,
            'data_kartu_stok' => $data_kartu_stok,
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'data_produk' => $this->produkModel->findAll(),
            'data_supplier' => $this->supplierModel->findAll(),
            'filter_sup' => $filter['supplier'],
            'filter_pro' => $filter['produk'],
            'filter_cab' => $filter['cabang'],
            'filter_supplier' => $filter_supplier_nama,
            'filter_produk' => $filter_produk_nama,
            'filter_cabang' => $filter_cabang_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('kartu_stok/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchKartuStok()
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
        $currentPage = $this->request->getVar('page_kartu_stok') ? $this->request->getVar('page_kartu_stok') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        */
        $filter = [
            'supplier' => $this->request->getGet('supplier') ? $this->request->getGet('supplier') : 0,
            'produk' => $this->request->getGet('produk') ? $this->request->getGet('produk') : 0,
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
        ];

        /*
        * Jika ada filter yang terdeteksi null, maka anggap saja tampilkan semua data
        */
        $filter['supplier'] = $filter['supplier'] ? $filter['supplier'] : 0;
        $filter['cabang'] = $filter['cabang'] ? $filter['cabang'] : 0;
        $filter['produk'] = $filter['produk'] ? $filter['produk'] : 0;

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
        * Menentukan sebutan filter untuk supplier
        */
        if ($filter['supplier'] != 0) {
            $filter_supplier = $this->supplierModel->find($filter['supplier']);
            $filter_supplier_nama = $filter_supplier['nama'];
        } else {
            $filter_supplier_nama = "Semua Supplier";
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
        * Mengambil hasil pencarian yang akan ditampilkan
        * beserta dengan link pagination
        */
        $kartuStokModel = $this->kartuStokModel->getKartuStok(false, $filter);
        $pager = $kartuStokModel['links'];
        $total = $kartuStokModel['total'];
        $perPage = $kartuStokModel['perPage'];

        $data_kartu_stok = $kartuStokModel['kartu_stok'];
        $data_cabang = $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray();

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari
        *
        * kantorName digunakan pada user title di header
        *
        * data_kartu_stok digunakan untuk menampilkan hasil pencarian pada tabel
        *
        * filter_cabang dan filter_keyword digunakan untuk menampilkan 
        * filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_kartu_stok' => $data_kartu_stok,
            'cabang' => $data_cabang,
            'filter_cabang' => $filter_cabang_nama,
            'filter_supplier' => $filter_supplier_nama,
            'filter_produk' => $filter_produk_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('kartu_stok/hasil-cari', $data);
    }

    /*
    * Menampilkan form pilih cabang sebelum 
    * menambah data stok awal baru
    */
    public function pilihCabang()
    {
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'kartu_stok',
            'title' => 'Pilih Cabang',
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
        ];

        return view('kartu_stok/pilih-cabang', $data);
    }

    /*
    * Menampilkan form untuk menambah stok awal baru
    */
    public function addStokAwal()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field cabang di form sebelumnya
        */
        $rules = [
            'cabang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Cabang harus diisi',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman pilih cabang 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('kartu-stok/pilih-cabang'))->withInput();
        }

        /*
        * Mengambil nilai slug cabang yang dikirimkan dari form pilih cabang
        */
        $slug_cabang = $this->request->getGet('cabang');

        /*
        * Mengambil informasi mengenai cabang terkait berdasarkan slug nya
        */
        $cabangModel = $this->cabangModel->where('slug', $slug_cabang)->first();

        /*
        * Jika cabang terdaftar, ambil id dan nama nya
        * Jika cabang tidak terdaftar, tampilkan 404 NOT FOUND
        */
        if ($cabangModel) {
            $cabang_id = $cabangModel['id'];
            $nama_cabang = $cabangModel['nama'];
        } else {
            $nama_cabang = 'Cabang tidak terdaftar.';
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cabang Tidak Terdaftar.');
        }

        /*
        * Mengirim data-data yang diperlukan pada add alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * data_produk digunakan menampilkan produk yang belum mempunyai stok awal
        * data_cabang dan slug cabang digunakan untuk menampilkan informasi
        * mengenai cabang yang akan ditambahkan stok awal pada salah satu produknya
        */
        $data = [
            'group' => 'kartu_stok',
            'title' => 'Tambah Kartu Stok',
            'kantorName' => $this->kantorName,
            'data_produk' => $this->kartuStokModel->getProdukNoStokAwal($cabang_id),
            'nama_cabang' => $nama_cabang,
            'slug_cabang' => $slug_cabang,
        ];

        return view('kartu_stok/tambah-stok-awal', $data);
    }

    /*
    * Menyimpan data stok awal baru
    */
    public function storeStokAwal()
    {
        /*
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kantor dan role digunakan untuk filter data
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_role = $user_login['role'];
        $user_login_kantor_slug = $user_login['slug_kantor'];

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add stok awal
        */
        $rules = [
            'produk' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Produk harus diisi',
                    'numeric' => 'Pilih produk yang tersedia',
                ]
            ],
            'stok_awal' => [
                'rules' => [
                    'required',
                    'regex_match[/^[0-9]+$/]',
                ],
                'errors' => [
                    'required' => 'Stok awal harus diisi',
                    'regex_match' => 'Jumlah harus berupa angka',
                ]
            ],
        ];

        /*
        * Mengambil nilai slug cabang yang dikirimkan dari form add stok awal
        */
        $slug_cabang = $this->request->getPost('cabang');

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add stok awal
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('kartu-stok/stok-awal?cabang=' . $slug_cabang))->withInput();
        }

        /*
        * Mengambil nilai id produk yang dikirimkan dari form add stok awal
        */
        $id_produk = $this->request->getVar('produk');

        /*
        * Mengambil informasi mengenai cabang terkait berdasarkan slugnya
        * untuk disimpan ke tabel kartu stok
        */
        $cabangModel = $this->cabangModel->where('slug', $slug_cabang)->first();
        $id_cabang = $cabangModel['id'];

        /*
        * Simpan stok awal ke tabel kartu stok
        */
        $this->kartuStokModel->save([
            'tanggal' => date('Y-m-d'),
            'no_bukti' => '-',
            'keterangan' => "Stok Awal",
            'id_produk' => $id_produk,
            'id_cabang' => $id_cabang,
            'stok_masuk' => $this->request->getVar('stok_awal'),
            'stok_keluar' => 0,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index kartu stok
        */
        session()->setFlashdata('berhasil', 'Stok Awal berhasil disimpan');
        return redirect()->to('/kartu-stok');
    }
}
