<?php

namespace App\Controllers;

use App\Models\CabangModel;
use App\Models\KonsumenModel;
use App\Models\UsersModel;

class Konsumen extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $konsumenModel;
    protected $usersModel;
    protected $cabangModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->konsumenModel = new KonsumenModel();
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
        $currentPage = $this->request->getGet('page_konsumen') ? $this->request->getGet('page_konsumen') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        * Filter keyword digunakan untuk pencarian konsumen
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
            'cabang' => $this->request->getGet('cabang') ? $this->request->getGet('cabang') : 0,
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
        * Mengambil data yang akan ditampilkan beserta dengan link pagination
        */
        $konsumenModel = $this->konsumenModel->getKonsumen(false, $filter);
        $pager = $konsumenModel['links'];
        $total = $konsumenModel['total'];
        $perPage = $konsumenModel['perPage'];
        $data_konsumen = $konsumenModel['konsumen'];

        /*
        * Mengirim data-data yang diperlukan pada view index konsumen
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_konsumen digunakan untuk menampilkan data konsumen pada tabel
        *
        * data_cabang digunakan untuk menampilkan pilihan filter cabang
        *
        * filter_k, filter_c, filter_cabang_nama
        * digunakan pada nilai filter yang sedang diterapkan
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'data',
            'title' => 'Konsumen',
            'kantorName' => $this->kantorName,
            'data_konsumen' => $data_konsumen,
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'filter_k' => $filter['keyword'],
            'filter_c' => $filter['cabang'],
            'filter_cabang_nama' => $filter_cabang_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('konsumen/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchKonsumen()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getGet('page_konsumen') ? $this->request->getGet('page_konsumen') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        * Filter keyword digunakan untuk pencarian konsumen
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
            'cabang' => $this->request->getGet('cabang') ? $this->request->getGet('cabang') : 0,
        ];

        /*
        * Jika tidak ada query keyword, maka anggap saja sebagai string kosong
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';

        /*
        * Jika tidak ada filter cabang, maka anggap saja sebagai int 0
        * (tampilkan semua data tanpa filter)
        */
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
        * Mengambil hasil pencarian data konsumen yang akan ditampilkan
        * beserta dengan link pagination
        */
        $konsumenModel = $this->konsumenModel->getKonsumen(false, $filter);
        $pager = $konsumenModel['links'];
        $total = $konsumenModel['total'];
        $perPage = $konsumenModel['perPage'];
        $data_konsumen = $konsumenModel['konsumen'];

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari konsumen
        *
        * kantorName digunakan pada user title di header
        *
        * data_konsumen digunakan untuk menampilkan hasil pencarian data konsumen pada tabel
        *
        * filter_keyword, filter_cabang digunakan untuk menampilkan filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_konsumen' => $data_konsumen,
            'filter_keyword' => $filter['keyword'],
            'filter_cabang' => $filter_cabang_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('konsumen/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data konsumen baru
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

        /*
        * Mengirim data-data yang diperlukan pada add konsumen
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * cabang_options digunakan pada opsi select cabang
        * user_kantor digunakan untuk menentukan 
        * apakah user bisa memilih cabang (role pusat) atau tidak (role cabang)
        */
        $data = [
            'group' => 'data',
            'title' => 'Tambah Data Konsumen',
            'kantorName' => $this->kantorName,
            'cabang_options' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'user_kantor' => $user_login_kantor_id,
        ];

        return view('konsumen/tambah', $data);
    }

    /*
    * Menyimpan data baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add konsumen
        */
        $rules = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama konsumen harus diisi',
                ]
            ],
            'alamat' => [
                'rules' => 'required|is_unique[konsumen.alamat]',
                'errors' => [
                    'required' => 'Alamat konsumen harus diisi',
                    'is_unique' => 'Alamat sudah terdaftar',
                ]
            ],
            'kota' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat kota konsumen harus diisi',
                ]
            ],
            'no_handphone' => [
                'rules' => 'required|regex_match[/^(?:\+62|62|0|021)(?:\d{8,15})$/]|is_unique[konsumen.no_telp]',
                'errors' => [
                    'required' => 'Nomor telepon konsumen harus diisi',
                    'regex_match' => 'Nomor telepon harus berupa 8-15 digit angka',
                    'is_unique' => 'Nomor telepon sudah terdaftar',
                ]
            ],
            'cabang' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Cabang lokasi konsumen harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add konsumen 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('konsumen/new'))->withInput();
        }

        /*
        * Membuat Slug dari nama konsumen
        */
        $newKonsumenName = $this->request->getVar('nama');
        $originalSlug = url_title($newKonsumenName, '-', true);

        /*
        * Karena nama konsumen tidak Unique, 
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
        * Simpan data ke tabel konsumen
        */
        $this->konsumenModel->save([
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'alamat' => $this->request->getVar('alamat'),
            'kota' => $this->request->getVar('kota'),
            'no_telp' => $this->request->getVar('no_handphone'),
            'id_cabang' => $id_cabang,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index konsumen
        */
        session()->setFlashdata('berhasil', 'Data konsumen baru berhasil ditambahkan');
        return redirect()->to('/konsumen');
    }

    /*
    * Membuat slug yang unique
    */
    private function _isSlugExists($slug)
    {
        /*
        * Check apakah slug konsumen sudah terdaftar di database
        */
        $konsumen = $this->konsumenModel->where('slug', $slug)->first();

        /*
        * Kembalikan nilai true jika sudah terdaftar atau false jika belum terdaftar
        */
        return $konsumen !== null;
    }

    /*
    * Menampilkan form untuk mengedit data konsumen
    */
    public function edit($slug)
    {
        /*
        * Mengambil informasi mengenai kantor dan role user yang sedang login
        * Kantor dan role digunakan untuk menentukan permission
        */
        $username_login = user()->username;
        $user_login = $this->usersModel->getUsers($username_login)['users'][0];
        $user_login_kantor_id = $user_login['kantor_id'];

        /*
        * Mengambil data konsumen berdasarkan slug nya dari database
        */
        $konsumen_db = $this->konsumenModel->getKonsumen($slug)['konsumen'];

        /*
        * Jika data konsumen tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($konsumen_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Konsumen ' . $slug . ' Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit konsumen
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan konsumen
        * yang ingin diedit, di input fields
        *
        * user_kantor digunakan untuk menentukan 
        * apakah user bisa memilih cabang (role pusat) atau tidak (role cabang)
        *
        * cabang_options digunakan pada opsi select cabang
        */
        $data = [
            'group' => 'data',
            'title' => 'Edit Data Konsumen',
            'kantorName' => $this->kantorName,
            'db' => $konsumen_db['0'],
            'cabang_options' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'user_kantor' => $user_login_kantor_id,
        ];

        return view('konsumen/edit', $data);
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
        * Mengambil informasi mengenai konsumen terkait dari model berdasarkan slug
        */
        $konsumen_db = $this->konsumenModel->getKonsumen($slug_db)['konsumen'];

        /*
        * Ambil data konsumen terkait
        */
        $konsumen_db = $konsumen_db[0];

        /*
        * Mengambil data alamat dan no_telp untuk unique validation
        */
        $alamat_db = $konsumen_db['alamat'];
        $noTelp_db = $konsumen_db['no_telp'];

        /*
        * Mendefinisikan validation rules untuk alamat dan no_telp
        * yang unique
        */
        $alamat_input = $this->request->getVar('alamat');
        if ($alamat_db == $alamat_input) {
            $rules_alamat = 'required';
        } else {
            $rules_alamat = 'required|is_unique[konsumen.alamat]';
        }

        $noTelp_input = $this->request->getVar('no_handphone');
        if ($noTelp_db == $noTelp_input) {
            $rules_noTelp = 'required|regex_match[/^(?:\+62|62|0|021)(?:\d{8,15})$/]';
        } else {
            $rules_noTelp = 'required|is_unique[konsumen.no_telp]|regex_match[/^(?:\+62|62|0|021)(?:\d{8,15})$/]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit konsumen
        */
        $rules = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama konsumen harus diisi',
                ]
            ],
            'alamat' => [
                'rules' => $rules_alamat,
                'errors' => [
                    'required' => 'Alamat konsumen harus diisi',
                    'is_unique' => 'Alamat sudah terdaftar',
                ]
            ],
            'kota' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat kota konsumen harus diisi',
                ]
            ],
            'no_handphone' => [
                'rules' => $rules_noTelp,
                'errors' => [
                    'required' => 'Nomor telepon konsumen harus diisi',
                    'regex_match' => 'Nomor telepon harus berupa 8-15 digit angka',
                    'is_unique' => 'Nomor telepon sudah terdaftar',
                ]
            ],
            'cabang' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Cabang lokasi konsumen harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit konsumen 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('konsumen/edit/' . $konsumen_db['slug']))->withInput();
        }

        /*
        * Membuat slug baru jika nama konsumen berubah
        */
        $newKonsumenName = $this->request->getVar('nama');
        $nama_db = $konsumen_db['nama'];

        if ($newKonsumenName != $nama_db) {
            $originalSlug = url_title($newKonsumenName, '-', true);

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
        * Simpan hasil edit ke tabel konsumen
        */
        $this->konsumenModel->save([
            'id' => $konsumen_db['id'],
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'alamat' => $this->request->getVar('alamat'),
            'kota' => $this->request->getVar('kota'),
            'no_telp' => $this->request->getVar('no_handphone'),
            'id_cabang' => $id_cabang,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index konsumen
        */
        session()->setFlashdata('berhasil', 'Data konsumen ' . $konsumen_db['nama'] . ' berhasil diedit');
        return redirect()->to('/konsumen');
    }

    /*
    * Menghapus data konsumen berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus konsumen berdasarkan id nya
        */
        $this->konsumenModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index konsumen
        */
        session()->setFlashdata('berhasil', 'Data Konsumen Berhasil Dihapus');
        return redirect()->to('/konsumen');
    }
}
