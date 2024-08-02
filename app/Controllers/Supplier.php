<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;
use App\Models\SupplierModel;

class Supplier extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $supplierModel;
    protected $usersModel;
    protected $cabangModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->supplierModel = new SupplierModel();
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
        $currentPage = $this->request->getGet('page_supplier') ? $this->request->getGet('page_supplier') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
        ];

        /*
        * Mengambil data yang akan ditampilkan
        * beserta dengan link pagination
        */
        $supplierModel = $this->supplierModel->getSupplier(false, $filter);
        $pager = $supplierModel['links'];
        $total = $supplierModel['total'];
        $perPage = $supplierModel['perPage'];
        $data_supplier = $supplierModel['supplier'];

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_supplier digunakan untuk menampilkan data supplier pada tabel
        *
        * data_kategori dan data_kategori digunakan untuk menampilkan pilihan filter cabang
        *
        * filter_k digunakan pada nilai filter 
        * yang sedang diterapkan
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'resources',
            'title' => 'Supplier',
            'kantorName' => $this->kantorName,
            'data_supplier' => $data_supplier,
            'filter_k' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('supplier/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchSupplier()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getGet('page_supplier') ? $this->request->getGet('page_supplier') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
        ];

        /*
        * Jika ada filter yang terdeteksi null, maka anggap saja tampilkan semua data
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';

        /*
        * Mengambil hasil pencarian yang akan ditampilkan
        * beserta dengan link pagination
        */
        $supplierModel = $this->supplierModel->getSupplier(false, $filter);
        $pager = $supplierModel['links'];
        $total = $supplierModel['total'];
        $perPage = $supplierModel['perPage'];
        $data_supplier = $supplierModel['supplier'];

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari alokasi
        *
        * kantorName digunakan pada user title di header
        *
        * data_supplier digunakan untuk menampilkan hasil pencarian data supplier pada tabel
        *
        * filter_keyword digunakan untuk menampilkan filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_supplier' => $data_supplier,
            'filter_keyword' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('supplier/hasil-cari', $data);
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
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'resources',
            'title' => 'Tambah Data Supplier'
        ];

        return view('supplier/tambah', $data);
    }

    /*
    * Menyimpan data baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add
        */
        $rules = [
            'nama' => [
                'rules' => 'required|is_unique[supplier.nama]',
                'errors' => [
                    'required' => 'Nama supplier harus diisi',
                    'is_unique' => 'Supplier sudah terdaftar',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('supplier/new'))->withInput();
        }

        /*
        * * Membuat Slug dari nama supplier
        */
        $newSupplierName = $this->request->getVar('nama');
        $originalSlug = url_title($newSupplierName, '-', true);

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
        * Simpan data ke tabel supplier
        */
        $this->supplierModel->save([
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index supplier
        */
        session()->setFlashdata('berhasil', 'Data supplier baru berhasil ditambahkan');
        return redirect()->to('/supplier');
    }

    /*
    * Membuat slug yang unique
    */
    private function _isSlugExists($slug)
    {
        /*
        * Check apakah slug supplier sudah terdaftar di database
        */
        $supplier = $this->supplierModel->where('slug', $slug)->first();

        /*
        * Kembalikan nilai true jika sudah terdaftar atau false jika belum terdaftar
        */
        return $supplier !== null;
    }

    /*
    * Menampilkan form untuk mengedit data supplier
    */
    public function edit($slug)
    {
        /*
        * Mengambil data supplier berdasarkan slug nya dari database
        */
        $supplier_db = $this->supplierModel->getSupplier($slug)['supplier'];

        /*
        * Jika tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($supplier_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Supplier ' . $slug . ' Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan data
        * yang ingin diedit, di input fields
        */
        $data = [
            'group' => 'resources',
            'title' => 'Edit Data Supplier',
            'kantorName' => $this->kantorName,
            'db' => $supplier_db['0']
        ];

        return view('supplier/edit', $data);
    }

    /*
    * Menyimpan data supplier yang diedit
    */
    public function update()
    {
        /*
        * Mengambil slug yang dikirimkan dari input hidden
        */
        $slug_db = $this->request->getVar('slug_db');

        /*
        * Mengambil informasi mengenai supplier terkait dari model berdasarkan slug
        */
        $supplier_db = $this->supplierModel->getSupplier($slug_db)['supplier'];

        /*
        * Ambil data terkait
        */
        $supplier_db = $supplier_db[0];
        $nama_db = $supplier_db['nama'];

        /*
        * Mendefinisikan validation rules untuk nama yang unique
        */
        $nama_input = $this->request->getVar('nama');
        if ($nama_db == $nama_input) {
            $rules_nama = 'required';
        } else {
            $rules_nama = 'required|is_unique[supplier.nama]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit
        */
        $rules = [
            'nama' => [
                'rules' => $rules_nama,
                'errors' => [
                    'required' => 'Nama supplier harus diisi',
                    'is_unique' => 'Supplier sudah terdaftar',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('supplier/edit/' . $supplier_db['slug']))->withInput();
        }

        /*
        * Membuat Slug dari nama
        * Karena nama Unique, maka sudah pasti nilai slug akan unique
        */
        $newSupplierName = $this->request->getVar('nama');
        $nama_db = $supplier_db['nama'];

        if ($newSupplierName != $nama_db) {
            $originalSlug = url_title($newSupplierName, '-', true);

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
        * Simpan hasil edit ke tabel supplier
        */
        $this->supplierModel->save([
            'id' => $supplier_db['id'],
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data Supplier ' . $supplier_db['nama'] . ' berhasil diedit');
        return redirect()->to('/supplier');
    }

    /*
    * Menghapus data berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus data berdasarkan id nya
        */
        $this->supplierModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data Supplier Berhasil Dihapus');
        return redirect()->to('/supplier');
    }
}
