<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;
use App\Models\ProdukModel;
use App\Models\AlokasiModel;
use App\Models\KartuStokModel;
use App\ValidationRules\MyRules;
use App\Models\AlokasiPerProdukModel;

class Alokasi extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan di halaman Alokasi
    */
    protected $alokasiModel;
    protected $cabangModel;
    protected $produkModel;
    protected $alokasiPerProdukModel;
    protected $kartuStokModel;
    protected $usersModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan di halaman Alokasi
        */
        $this->alokasiModel = new AlokasiModel();
        $this->cabangModel = new CabangModel();
        $this->produkModel = new ProdukModel();
        $this->alokasiPerProdukModel = new alokasiPerProdukModel();
        $this->kartuStokModel = new KartuStokModel();
        $this->usersModel = new UsersModel();

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
    * Menampilkan data alokasi di halaman utama alokasi
    */
    public function index(): string
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_alokasi') ? $this->request->getVar('page_alokasi') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Data yang ditampilkan secara default hanya data  pada bulan saat ini
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
            'cabang' => $this->request->getGet('cabang') ? $this->request->getGet('cabang') : 0,
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
        * Mengambil data alokasi yang akan ditampilkan
        * beserta dengan link pagination
        */
        $alokasiModel = $this->alokasiModel->getAlokasi(false, $filter);
        $pager = $alokasiModel['links'];
        $total = $alokasiModel['total'];
        $perPage = $alokasiModel['perPage'];
        $data_alokasi = $alokasiModel['alokasi'];

        /*
        * Mengambil nilai tahun minimal yang ada pada data untuk 
        * kebutuhan filter tahun
        */
        if ($this->alokasiModel->getMinYear()) {
            $tahun_mulai = $this->alokasiModel->getMinYear();
        } else {
            $tahun_mulai = date('Y');
        }

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_alokasi digunakan untuk menampilkan data alokasi pada tabel
        *
        * data_cabang digunakan untuk menampilkan pilihan filter cabang
        * tahun_mulai digunakan untuk menampilkan pilihan filter tahun
        *
        * filter_cabang, filter_bul, dan filter_tah digunakan pada nilai filter 
        * yang sedang diterapkan
        *
        * filter_cabang_nama dan filter_waktu digunakan pada sebutan
        * filter yang sedang diterapkan, di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'alokasi',
            'title' => 'Alokasi',
            'data_alokasi' => $data_alokasi,
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'data_produk' => $this->produkModel->findAll(),
            'tahun_mulai' => $tahun_mulai,
            'filter_key' => $filter['keyword'],
            'filter_cabang' => $filter['cabang'],
            'filter_pro' => $filter['produk'],
            'filter_bul' => $filter['bulan'],
            'filter_tah' => $filter['tahun'],
            'filter_cabang_nama' => $filter_cabang_nama,
            'filter_produk_nama' => $filter_produk_nama,
            'filter_waktu' => $filter_waktu,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('alokasi/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchAlokasi()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_alokasi') ? $this->request->getVar('page_alokasi') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Data yang ditampilkan hanya data pada bulan saat ini
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
            'cabang' => $this->request->getGet('cabang') ? $this->request->getGet('cabang') : 0,
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
        $alokasiModel = $this->alokasiModel->getAlokasi(false, $filter);
        $pager = $alokasiModel['links'];
        $total = $alokasiModel['total'];
        $perPage = $alokasiModel['perPage'];
        $data_alokasi = $alokasiModel['alokasi'];

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari alokasi
        *
        * kantorName digunakan pada user title di header
        *
        * data_alokasi digunakan untuk menampilkan hasil pencarian data alokasi pada tabel
        *
        * filter_cabang_nama, filter_waktu digunakan untuk menampilkan filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_alokasi' => $data_alokasi,
            'filter_cabang_nama' => $filter_cabang_nama,
            'filter_produk_nama' => $filter_produk_nama,
            'filter_waktu' => $filter_waktu,
            'filter_key' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('alokasi/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data alokasi baru
    */
    public function add()
    {
        /*
        * Mengirim data-data yang diperlukan pada add alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * data_cabang digunakan pada opsi select cabang
        * data_cabang digunakan pada opsi select produk
        */
        $data = [
            'group' => 'alokasi',
            'title' => 'Tambah Data Alokasi',
            'kantorName' => $this->kantorName,
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'data_produk' => $this->produkModel->findAll(),
        ];

        return view('alokasi/tambah', $data);
    }

    /*
    * Menyimpan data alokasi baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add alokasi
        */
        $rules = [
            'cabang' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Cabang harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
                ]
            ],
            'produk.*' => [
                'rules' => [
                    'required',
                    'numeric',
                ],
                'errors' => [
                    'required' => 'Produk harus diisi',
                    'numeric' => 'Pillih produk yang tersedia',
                ]
            ],
            'jumlah.*' => [
                'rules' => [
                    'required',
                    'regex_match[/^[0-9]+$/]',
                    'required_with[produk]',
                ],
                'errors' => [
                    'required' => 'Jumlah produk harus diisi',
                    'regex_match' => 'Jumlah harus berupa angka',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add alokasi 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('alokasi/new'))->withInput();
        }

        /*
        * Melakukan Validasi Lanjutan untuk produk
        * 
        * 1. Ambil inputnya 
        * 2. Validasi dengan function unique_value di app\ValidationRules\MyRules.php
        * 3. Jika tidak valid, kembali ke halaman add alokasi dan tampilkan error pada field yang tidak valid
        */
        $input = $this->request->getPost();
        $productErrors = MyRules::unique_value($input);
        if (!empty($productErrors)) {
            foreach ($productErrors as $index => $message) {
                $this->validator->setError("produk.$index", $message);
            }
            return redirect()->to(base_url('alokasi/new'))->withInput();
        }

        // Ambil kode_cabang untuk membuat nomor alokasi baru 
        $id_cabang = $this->request->getPost('cabang');
        $cabang_db = $this->cabangModel->find($id_cabang);
        $kode_cabang = $cabang_db['kode_cabang'];

        /*
        * Membuat nomor alokasi baru
        */
        $lastAlokasi = $this->alokasiModel->getLastAlokasiNumber($kode_cabang);
        if (!$lastAlokasi) {
            $no_alokasi = 'ALK/' . $kode_cabang . '/' . date('Y/m') . '/001';
        } else {
            $lastAlokasiNumber = $lastAlokasi['no_alokasi'];
            $lastAlokasiNumber = intval(substr($lastAlokasiNumber, -3));
            $incrementedNumber = str_pad($lastAlokasiNumber + 1, 3, '0', STR_PAD_LEFT);;
            $no_alokasi = 'RTR/' . $kode_cabang . '/' . date('Y/m') . '/' . $incrementedNumber;
        }

        /*
        * Simpan data tanggal dan cabang alokasi ke tabel alokasi
        */
        $this->alokasiModel->save([
            'tanggal' => date('Y-m-d'),
            'id_cabang' => $this->request->getPost('cabang'),
            'no_alokasi' => $no_alokasi,
        ]);

        /*
        * Ambil id data lokasi yang baru saja disimpan
        */
        $id_alokasi = $this->alokasiModel->insertID();

        /*
        * Mengambil nilai-nilai produk yang diinput beserta dengan jumlahnya
        */
        $produkValues = $this->request->getPost('produk');
        $jumlahValues = $this->request->getPost('jumlah');

        /*
        * Simpan setiap produk yang diinput beserta dengan jumlahnya ke dalam database
        */
        foreach ($produkValues as $index => $p) {
            /*
            * Simpan ke dalam tabel alokasi_per_produk
            */
            $this->alokasiPerProdukModel->save([
                'id_alokasi' => $id_alokasi,
                'id_produk' => $p,
                'jumlah' => $jumlahValues[$index],
            ]);

            /*
            * Simpan ke dalam tabel kartu_stok
            */
            $this->kartuStokModel->save([
                'tanggal' => date('Y-m-d'),
                'no_bukti' => $no_alokasi,
                'keterangan' => 'Alokasi',
                'id_produk' => $p,
                'id_cabang' => $this->request->getVar('cabang'),
                'stok_masuk' => $jumlahValues[$index],
                'stok_keluar' => 0,
            ]);
        }

        /*
        * Atur pesan berhasil dan kembali ke halaman index alokasi
        */
        session()->setFlashdata('berhasil', 'Data alokasi berhasil ditambahkan');
        return redirect()->to('/alokasi');
    }

    /*
    * Menampilkan detail alokasi berupa produk-produknya
    */
    public function detail($id)
    {
        /*
        * Mengambil id alokasi yang ingin ditampilkan detailnya
        */
        $id_alokasi = $id;

        /*
        * Mengambil data alokasi berdasarkan id nya dari database
        */
        $alokasi_db = $this->alokasiModel->getAlokasi($id_alokasi)['alokasi'];
        $no_alokasi = $alokasi_db[0]['no_alokasi'];

        /*
        * Jika data alokasi tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($alokasi_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Alokasi Tidak Ditemukan');
        }

        /*
        * Ambil nama produk di alokasi terkait
        */
        $alokasi_produks = $this->alokasiPerProdukModel->getAlokasiPerProduk($id_alokasi);

        /*
        * Mengirim data-data yang diperlukan pada halaman detail alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * cabang_alokasi dan tanggal_alokasi digunakan pada keterangan alokasi
        * alokasi_produks digunakan pada data produk alokasi terkait yang ditampilkan pada tabel
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'alokasi',
            'title' => 'Alokasi',
            'no_alokasi' => $no_alokasi,
            'alokasi_produks' => $alokasi_produks,
        ];

        return view('alokasi/detail', $data);
    }

    /*
    * Menampilkan form untuk mengedit data produk alokasi
    */
    public function editProduk($id)
    {
        /*
        * Mengambil id produk alokasi yang ingin diedit
        */
        $id_produk_alokasi = $id;

        /*
        * Mengambil data produk alokasi berdasarkan id nya dari database
        */
        $alokasiPerProduk_db = $this->alokasiPerProdukModel->findProdukAlokasi($id_produk_alokasi);

        /*
        * Jika data produk alokasi tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($alokasiPerProduk_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Produk Alokasi Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan produk pada alokasi terkait 
        * yang ingin diedit, di input fields
        */
        $data = [
            'group' => 'alokasi',
            'title' => 'Edit Produk Alokasi',
            'kantorName' => $this->kantorName,
            'db' => $alokasiPerProduk_db['0']
        ];

        return view('alokasi/edit-produk', $data);
    }

    /*
    * Menyimpan data produk alokasi yang diedit
    */
    public function updateProduk()
    {
        /*
        * Mengambil id produk alokasi yang diedit
        */
        $id_alokasiPerProduk = $this->request->getPost('id_alokasiPerProduk');

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit produk alokasi
        */
        $rules = [
            'jumlah' => [
                'rules' => 'required|regex_match[/^[0-9]+$/]',
                'errors' => [
                    'required' => 'Jumlah harus diisi',
                    'regex_match' => 'Jumlah harus berupa angka'
                ],
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit produk alokasi
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('alokasi-produk/edit/' . $id_alokasiPerProduk))->withInput();
        }

        /*
        * Mengambil informasi dari database mengenai produk yang diedit
        * diperlukan karena kita memerlukan id alokasi, created_at, dan id_produk
        * untuk update baris di tabel kartu_stok
        */
        $alokasiPerProduk_db = $this->alokasiPerProdukModel->findProdukAlokasi($id_alokasiPerProduk)[0];
        $id_alokasi = $alokasiPerProduk_db['id_alokasi'];
        $ca_alokasiPerProduk = $alokasiPerProduk_db['created_at'];
        $id_produk = $alokasiPerProduk_db['id_produk'];

        /*
        * Mengambil informasi dari alokasi dari produk untuk mendapatkan id_cabang
        */
        $alokasiProduk_db = $this->alokasiModel->getAlokasi($id_alokasi)['alokasi'][0];
        $id_cabang = $alokasiProduk_db['id_cabang'];

        /*
        * Mengambil id baris kartu_stok terkait produk alokasi yang ingin diedit
        * berdasarkan id_cabang, id_produk, dan created_at produk alokasi pada tabel kartu_stok
        */
        $kartu_stok_row = $this->alokasiPerProdukModel->findKartuStok($id_cabang, $id_produk, $ca_alokasiPerProduk)[0];
        $id_kartu_stok = $kartu_stok_row['id'];

        /*
        * Simpan hasil edit ke dalam tabel alokasi_per_produk
        */
        $this->alokasiPerProdukModel->save([
            'id' => $id_alokasiPerProduk,
            'jumlah' => $this->request->getPost('jumlah'),
        ]);

        /*
        * Simpan hasil edit ke dalam tabel kartu_stok
        */
        $this->kartuStokModel->save([
            'id' => $id_kartu_stok,
            'stok_masuk' => $this->request->getPost('jumlah'),
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index alokasi
        */
        session()->setFlashdata('berhasil', 'Data alokasi berhasil diedit');
        return redirect()->to('/alokasi/' . $id_alokasi);
    }
}
