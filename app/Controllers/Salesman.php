<?php

namespace App\Controllers;

use App\Models\CabangModel;
use App\Models\SalesmanModel;
use App\Models\UsersModel;

class Salesman extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $salesmanModel;
    protected $cabangModel;
    protected $usersModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->salesmanModel = new SalesmanModel();
        $this->cabangModel = new CabangModel();
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
    * Menampilkan data kategori di halaman utama Kategori
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

        $currentPage = $this->request->getVar('page_salesman') ? $this->request->getVar('page_salesman') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
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
        * Mengambil data salesman yang akan ditampilkan 
        * beserta dengan link pagination
        */
        $salesmanModel = $this->salesmanModel->getSalesman(false, $filter);
        $pager = $salesmanModel['links'];
        $total = $salesmanModel['total'];
        $perPage = $salesmanModel['perPage'];
        $data_salesman = $salesmanModel['salesman'];

        /*
        * Mengirim data-data yang diperlukan pada view index mutasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_salesman digunakan untuk menampilkan data salesman pada tabel
        *
        * data_cabang digunakan untuk menampilkan pilihan filter cabang
        *
        * filter_k dan filter_cdigunakan pada nilai filter 
        * yang sedang diterapkan
        *
        * filter_cabang_namadigunakan pada sebutan
        * filter yang sedang diterapkan, di dalam tabel
        * 
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'data',
            'title' => 'Salesman',
            'kantorName' => $this->kantorName,
            'data_salesman' => $data_salesman,
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'filter_k' => $filter['keyword'],
            'filter_c' => $filter['cabang'],
            'filter_cabang_nama' => $filter_cabang_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('salesman/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchSalesman()
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
        $currentPage = $this->request->getVar('page_salesman') ? $this->request->getVar('page_salesman') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
            'cabang' => $user_login_role == 'pusat' ? $this->request->getGet('cabang') : $user_login_kantor_id,
        ];

        /*
        * Jika ada filter yang terdeteksi null, maka anggap saja tampilkan semua data
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';
        $filter['cabang'] = $filter['cabang'] ? $filter['cabang'] : 0;

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
        * Mengambil hasil pencarian yang akan ditampilkan
        * beserta dengan link pagination
        */
        $salesmanModel = $this->salesmanModel->getSalesman(false, $filter);
        $pager = $salesmanModel['links'];
        $total = $salesmanModel['total'];
        $perPage = $salesmanModel['perPage'];

        $data_salesman = $salesmanModel['salesman'];
        $data_cabang = $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray();

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari
        *
        * kantorName digunakan pada user title di header
        *
        * data_salesman digunakan untuk menampilkan hasil pencarian pada tabel
        *
        * filter_cabang dan filter_keyword digunakan untuk menampilkan 
        * filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_salesman' => $data_salesman,
            'cabang' => $data_cabang,
            'filter_cabang' => $filter_cabang_nama,
            'filter_keyword' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('salesman/hasil-cari', $data);
    }

    public function add()
    {
        // Ambil data id_kantor yang user yang sedang login untuk dikirimkan ke input hidden
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];

        /*
        * Mengirim data-data yang diperlukan pada add salesman
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * cabang_options digunakan untuk opsi cabang
        * user_kantor digunakan untuk mendefinisikan 
        * id cabang salesman yang ditambahkan (jika yang 
        * menambahkan adalah role cabang)
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'data',
            'title' => 'Tambah Data Salesman',
            'cabang_options' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'user_kantor' => $user_login_kantor_id,
        ];

        return view('salesman/tambah', $data);
    }

    /*
    * Menyimpan data kategori baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add salesman
        */
        $rules = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama salesman harus diisi',
                ]
            ],
            'no_handphone' => [
                'rules' => 'required|regex_match[/^(?:\+62|62|0|021)(?:\d{8,15})$/]|is_unique[salesman.no_telp]',
                'errors' => [
                    'required' => 'Nomor telepon harus salesman diisi',
                    'regex_match' => 'Nomor telepon harus diisi dengan 8-15 digit angka',
                    'is_unique' => 'Nomor telepon sudah terdaftar',
                ]
            ],
            'cabang' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Cabang untuk salesman harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add salesman 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('salesman/new'))->withInput();
        }

        /*
        * Membuat Slug dari nama
        */
        $newSalesmanName = $this->request->getVar('nama');
        $originalSlug = url_title($newSalesmanName, '-', true);

        /*
        * Karena nama tidak Unique, 
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
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kantor dan role digunakan untuk menentukan permission
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];

        /*
        * Jika role user adalah pusat, maka ambil nilai yang dikirimkan dari form
        * Jika role user adalah cabang, maka ambil nilai id cabang user
        */
        $id_cabang = $user_login['role'] == 'pusat' ? $this->request->getPost('cabang') : $user_login_kantor_id;

        /*
        * Simpan data ke tabel salesman
        */
        $this->salesmanModel->save([
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'no_telp' => $this->request->getVar('no_handphone'),
            'id_cabang' => $id_cabang,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index salesman
        */
        session()->setFlashdata('berhasil', 'Data salesman baru berhasil ditambahkan');
        return redirect()->to('/salesman');
    }

    /*
    * Membuat slug yang unique
    */
    private function _isSlugExists($slug)
    {
        /*
        * Check apakah slug salesman sudah terdaftar di database
        */
        $salesman = $this->salesmanModel->where('slug', $slug)->first();

        /*
        * Kembalikan nilai true jika sudah terdaftar atau false jika belum terdaftar
        */
        return $salesman !== null;
    }

    /*
    * Menampilkan form untuk mengedit data salesman
    */
    public function edit($slug)
    {
        /*
        * Mengambil data salesman berdasarkan slug nya dari database
        */
        $salesman_db = $this->salesmanModel->getSalesman($slug)['salesman'];

        /*
        * Jika data tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($salesman_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Salesman ' . $slug . ' Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan data
        * yang ingin diedit, di input fields
        *
        * cabang_options digunakan pada opsi select cabang
        */
        $data = [
            'group' => 'data',
            'title' => 'Edit Data Salesman',
            'kantorName' => $this->kantorName,
            'db' => $salesman_db['0'],
            'cabang_options' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
        ];


        return view('salesman/edit', $data);
    }

    /*
    * Menyimpan data konsumen yang diedit
    */
    public function update()
    {
        /*
        * Mengambil slug yang dikirimkan dari input hidden
        */
        $slug_db = $this->request->getVar('slug_db');

        /*
        * Mengambil informasi mengenai data terkait dari model berdasarkan slug
        */
        $salesman_db = $this->salesmanModel->getSalesman($slug_db)['salesman'];

        /*
        * Ambil data salesman terkait
        */
        $salesman_db = $salesman_db[0];

        /*
        * Mengambil data no_telp untuk unique validation
        */
        $noTelp_db = $salesman_db['no_telp'];

        /*
        * Mendefinisikan validation rules untuk no_telp
        * yang unique
        */
        $noTelp_input = $this->request->getVar('no_handphone');
        if ($noTelp_db == $noTelp_input) {
            $rules_noTelp = 'required|regex_match[/^(?:\+62|62|0|021)(?:\d{8,15})$/]';
        } else {
            $rules_noTelp = 'required|is_unique[salesman.no_telp]|regex_match[/^(?:\+62|62|0|021)(?:\d{8,15})$/]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit
        */
        $rules = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus diisi',
                ]
            ],
            'no_handphone' => [
                'rules' => $rules_noTelp,
                'errors' => [
                    'required' => 'Nomor telepon harus diisi',
                    'regex_match' => 'Nomor telepon harus diisi dengan 8-15 digit angka',
                    'is_unique' => 'Nomor telepon sudah terdaftar',
                ]
            ],
            'cabang' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Cabang untuk salesman harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
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
            return redirect()->to(base_url('salesman/edit/' . $salesman_db['slug']))->withInput();
        }

        /*
        * Membuat slug baru jika nama salesman berubah
        */
        $newSalesmanName = $this->request->getVar('nama');
        $nama_db = $salesman_db['nama'];

        if ($newSalesmanName != $nama_db) {
            $originalSlug = url_title($newSalesmanName, '-', true);

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
        * Simpan hasil edit ke tabel salesman
        */
        $this->salesmanModel->save([
            'id' => $salesman_db['id'],
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'no_telp' => $this->request->getVar('no_handphone'),
            'id_cabang' => $this->request->getVar('cabang'),
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data salesman ' . $salesman_db['nama'] . ' berhasil diedit');
        return redirect()->to('/salesman');
    }

    /*
    * Menghapus data berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus data berdasarkan id nya
        */
        $this->salesmanModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data Salesman Berhasil Dihapus');
        return redirect()->to('/salesman');
    }
}
