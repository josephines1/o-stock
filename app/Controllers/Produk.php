<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;
use App\Models\ProdukModel;
use App\Models\SupplierModel;
use App\Models\KartuStokModel;
use App\Controllers\BaseController;
use App\Models\KategoriProdukModel;

class Produk extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $produkModel;
    protected $kategoriModel;
    protected $supplierModel;
    protected $kartuStokModel;
    protected $usersModel;
    protected $cabangModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriProdukModel();
        $this->supplierModel = new SupplierModel();
        $this->kartuStokModel = new KartuStokModel();
        $this->usersModel = new UsersModel();
        $this->cabangModel = new CabangModel();

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
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_produk') ? $this->request->getVar('page_produk') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getPost('keyword') ? $this->request->getPost('keyword') : '',
            'kategori' => $this->request->getPost('kategori') ? $this->request->getPost('kategori') : 0,
            'supplier' => $this->request->getPost('supplier') ? $this->request->getPost('supplier') : 0,
        ];

        /*
        * Menentukan sebutan filter untuk kategori
        */
        if ($filter['kategori'] != 0) {
            $filter_kategori = $this->kategoriModel->find($filter['kategori']);
            $filter_kategori_nama = $filter_kategori['name'];
        } else {
            $filter_kategori_nama = "Semua Kategori";
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
        * Mengambil data yang akan ditampilkan
        * beserta dengan link pagination
        */
        $produkModel = $this->produkModel->getProduk(false, $filter);
        $pager = $produkModel['links'];
        $total = $produkModel['total'];
        $perPage = $produkModel['perPage'];
        $data_produk = $produkModel['produk'];

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_produk digunakan untuk menampilkan data produk pada tabel
        *
        * data_kategori dan data_kategori digunakan untuk menampilkan pilihan filter
        *
        * filter_key, filter_kat, dan filter_sup digunakan pada nilai filter 
        * yang sedang diterapkan
        *
        * filter_kategori dan filter_supplier digunakan pada sebutan
        * filter yang sedang diterapkan, di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'resources',
            'title' => 'Produk',
            'kantorName' => $this->kantorName,
            'data_produk' => $data_produk,
            'data_kategori' => $this->kategoriModel->findAll(),
            'data_supplier' => $this->supplierModel->findAll(),
            'filter_key' => $filter['keyword'],
            'filter_kat' => $filter['kategori'],
            'filter_sup' => $filter['supplier'],
            'filter_kategori' => $filter_kategori_nama,
            'filter_supplier' => $filter_supplier_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('produk/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchProduk()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_produk') ? $this->request->getVar('page_produk') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
            'kategori' => $this->request->getGet('kategori'),
            'supplier' => $this->request->getGet('supplier'),
        ];

        /*
        * Jika ada filter yang terdeteksi null, maka anggap saja tampilkan semua data
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';
        $filter['kategori'] = $filter['kategori'] ? $filter['kategori'] : 0;
        $filter['supplier'] = $filter['supplier'] ? $filter['supplier'] : 0;

        /*
        * Menentukan sebutan filter untuk kategori
        */
        if ($filter['kategori'] != 0) {
            $filter_kategori = $this->kategoriModel->find($filter['kategori']);
            $filter_kategori_nama = $filter_kategori['nama'];
        } else {
            $filter_kategori_nama = "Semua Kategori";
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
        * Mengambil hasil pencarian yang akan ditampilkan
        * beserta dengan link pagination
        */
        $produkModel = $this->produkModel->getProduk(false, $filter);
        $pager = $produkModel['links'];
        $total = $produkModel['total'];
        $perPage = $produkModel['perPage'];
        $data_produk = $produkModel['produk'];

        $data_kategori = $this->kategoriModel->findAll();
        $data_supplier = $this->supplierModel->findAll();

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari alokasi
        *
        * kantorName digunakan pada user title di header
        *
        * data_produk digunakan untuk menampilkan hasil pencarian data produk pada tabel
        *
        * filter_keyword, filter_kategori, dan filter_supplier digunakan untuk menampilkan filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_produk' => $data_produk,
            'kategori' => $data_kategori,
            'supplier' => $data_supplier,
            'filter_keyword' => $filter['keyword'],
            'filter_kategori' => $filter_kategori_nama,
            'filter_supplier' => $filter_supplier_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('produk/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data baru
    */
    public function add()
    {
        /*
        * Mengirim data-data yang diperlukan pada add produk
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * data_kategori digunakan pada opsi select kategori
        * data_supplier digunakan pada opsi select supplier
        */
        $data = [
            'group' => 'resources',
            'title' => 'Tambah Data Produk',
            'kantorName' => $this->kantorName,
            'data_kategori' => $this->kategoriModel->findAll(),
            'data_supplier' => $this->supplierModel->findAll(),
        ];

        return view('produk/tambah', $data);
    }

    /*
    * Menyimpan data baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add produk
        */
        $rules = [
            'kode_produk' => [
                'rules' => 'required|is_unique[produk.kode_produk]',
                'errors' => [
                    'required' => 'Kode produk harus diisi',
                    'is_unique' => 'Kode produk sudah terdaftar',
                ],
            ],
            'nama' => [
                'rules' => 'required|is_unique[produk.nama]',
                'errors' => [
                    'required' => 'Nama produk harus diisi',
                    'is_unique' => 'Nama produk sudah terdaftar',
                ]
            ],
            'harga_jual' => [
                'rules' => 'required|regex_match[/^[0-9.]+$/]',
                'errors' => [
                    'required' => 'Harga jual produk harus diisi',
                    'regex_match' => 'Contoh Format penulisan: 150.000',
                ]
            ],
            'kategori' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kategori produk harus dipilih',
                    'numeric' => 'Pilih kategori yang tersedia',
                ]
            ],
            'supplier' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Supplier produk harus dipilih',
                    'numeric' => 'Pilih supplier yang tersedia',
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
            return redirect()->to(base_url('/produk/new'))->withInput();
        }

        /*
        * Menyesuaikan format harga jual 
        */
        $harga_jual = str_replace('.', '', $this->request->getVar('harga_jual'));

        /*
        * Membuat Slug dari nama produk
        */
        $newProdukName = $this->request->getVar('nama');
        $originalSlug = url_title($newProdukName, '-', true);

        /*
        * Karena nama tidak bisa saja mengandung tanda baca yang tidak terdeteksi di url_title, 
        * maka kita perlu atur agar setiap slug bernilai unique
        */
        $slug = $originalSlug;
        $counter = 2;

        /*
        * Kita gunakan private method _isSlugExist untuk
        * menambahkan angka di akhir slug jika slug original
        * nya sudah terdaftar di database
        */
        while ($this->_isSlugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        /*
        * Simpan data ke tabel produk
        */
        $this->produkModel->save([
            'kode_produk' => $this->request->getVar('kode_produk'),
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'harga_jual' => $harga_jual,
            'id_kategori' => $this->request->getVar('kategori'),
            'id_supplier' => $this->request->getVar('supplier'),
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index kategori
        */
        session()->setFlashdata('berhasil', 'Data produk berhasil ditambahkan');
        return redirect()->to('/produk');
    }

    /*
    * Membuat slug yang unique
    */
    private function _isSlugExists($slug)
    {
        /*
        * Check apakah slug produk sudah terdaftar di database
        */
        $produk = $this->produkModel->where('slug', $slug)->first();

        /*
        * Kembalikan nilai true jika sudah terdaftar atau false jika belum terdaftar
        */
        return $produk !== null;
    }

    /*
    * Menampilkan form untuk mengedit data produk
    */
    public function edit($slug)
    {
        /*
        * Mengambil data produk berdasarkan slug nya dari database
        */
        $produk_db = $this->produkModel->getProduk($slug)['produk'];

        /*
        * Jika tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($produk_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Produk ' . $slug . ' Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan produk
        * yang ingin diedit, di input fields
        *
        * data_kategori digunakan pada opsi select kategori
        * data_supplier digunakan pada opsi select supplier
        */
        $data = [
            'group' => 'resources',
            'title' => 'Edit Data Produk',
            'kantorName' => $this->kantorName,
            'db' => $produk_db['0'],
            'data_kategori' => $this->kategoriModel->findAll(),
            'data_supplier' => $this->supplierModel->findAll(),
        ];

        return view('produk/edit', $data);
    }

    /*
    * Menyimpan data produk yang diedit
    */
    public function update()
    {
        /*
        * Mengambil slug yang dikirimkan dari input hidden
        */
        $slug_db = $this->request->getVar('slug_db');

        /*
        * Mengambil informasi mengenai produk terkait dari model berdasarkan slug
        */
        $produk_db = $this->produkModel->getProduk($slug_db)['produk'];

        /*
        * Ambil data produk terkait
        */
        $produk_db = $produk_db[0];
        $kode_db = $produk_db['kode_produk'];
        $nama_db = $produk_db['nama'];

        /*
        * Mendefinisikan validation rules untuk kode dan nama yang unique
        */
        $kode_input = $this->request->getVar('kode_produk');
        if ($kode_db == $kode_input) {
            $rules_kode = 'required';
        } else {
            $rules_kode = 'required|is_unique[produk.kode_produk]';
        }

        $nama_input = $this->request->getVar('nama');
        if ($nama_db == $nama_input) {
            $rules_nama = 'required';
        } else {
            $rules_nama = 'required|is_unique[produk.nama]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit produk
        */
        $rules = [
            'kode_produk' => [
                'rules' => $rules_kode,
                'errors' => [
                    'required' => 'Kode produk harus diisi',
                    'is_unique' => 'Kode produk sudah terdaftar',
                ]
            ],
            'nama' => [
                'rules' => $rules_nama,
                'errors' => [
                    'required' => 'Nama produk harus diisi',
                    'is_unique' => 'Nama produk sudah terdaftar',
                ]
            ],
            'harga_jual' => [
                'rules' => 'required|regex_match[/^[0-9.]+$/]',
                'errors' => [
                    'required' => 'Harga jual produk harus diisi',
                    'regex_match' => 'Contoh Format penulisan: 150.000',
                ]
            ],
            'kategori' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kategori produk harus dipilih',
                    'numeric' => 'Pilih kategori yang tersedia',
                ]
            ],
            'supplier' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Supplier produk harus dipilih',
                    'numeric' => 'Pilih supplier yang tersedia',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit produk 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('produk/edit/' . $produk_db['slug']))->withInput();
        }

        /*
        * Menyesuaikan format harga jual 
        */
        $harga_jual = str_replace('.', '', $this->request->getVar('harga_jual'));

        /*
        * * Membuat Slug dari nama produk
        */
        $newProdukName = $this->request->getVar('nama');
        $nama_db = $produk_db['nama'];

        if ($newProdukName != $nama_db) {
            $originalSlug = url_title($newProdukName, '-', true);

            $slug = $originalSlug;
            $counter = 2;

            while ($this->_isSlugExists($slug)) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        } else {
            $slug = $slug_db;
        }

        /*
        * Simpan hasil edit ke tabel produk
        */
        $this->produkModel->save([
            'id' => $produk_db['id'],
            'kode_produk' => $this->request->getVar('kode_produk'),
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'harga_jual' => $harga_jual,
            'id_kategori' => $this->request->getVar('kategori'),
            'id_supplier' => $this->request->getVar('supplier'),
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index produk
        */
        session()->setFlashdata('berhasil', 'Data produk ' . $produk_db['nama'] . ' berhasil diedit');
        return redirect()->to('/produk');
    }

    /*
    * Menghapus data produk berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus produk berdasarkan id nya
        */
        $this->produkModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data Produk Berhasil Dihapus');
        return redirect()->to('/produk');
    }
}
