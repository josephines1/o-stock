<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;

class Cabang extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $cabangModel;
    protected $tipe_default;
    protected $usersModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->cabangModel = new CabangModel();
        $this->tipe_default = 'cabang';
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
    * Menampilkan data di halaman utama
    */
    public function index(): string
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getGet('page_cabang') ? $this->request->getGet('page_cabang') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Hanya user dengan role pusat yang dapat mengakses halaman Cabang
        * Filter keyword digunakan untuk pencarian cabang
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
        ];

        /*
        * Mengambil data yang akan ditampilkan beserta dengan link pagination
        */
        $cabangModel = $this->cabangModel->getCabang(false, $filter);
        $pager = $cabangModel['links'];
        $total = $cabangModel['total'];
        $perPage = $cabangModel['perPage'];
        $data_cabang = $cabangModel['cabang'];

        /*
        * Mengirim data-data yang diperlukan pada view index cabang
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_cabang digunakan untuk menampilkan data cabang pada tabel
        *
        * filter_k digunakan untuk menampilkan keyword pencarian yang sedang diterapkan
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'data',
            'title' => 'Pusat & Cabang',
            'kantorName' => $this->kantorName,
            'data_cabang' => $data_cabang,
            'filter_k' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('cabang/index', $data);
    }

    /*
    * Menampilkan hasil pencarian data cabang
    */
    public function searchCabang()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getGet('page_salesman') ? $this->request->getGet('page_salesman') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * Hanya user dengan role pusat yang dapat mengakses halaman Cabang
        * Filter keyword digunakan untuk pencarian cabang
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
        ];

        /*
        * Mengambil nilai string keyword pencarian untuk ditampilkan pada tabel
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';

        /*
        * Mengambil hasil pencarian data cabang yang akan ditampilkan beserta dengan link pagination
        */
        $cabangModel = $this->cabangModel->getCabang(false, $filter);
        $pager = $cabangModel['links'];
        $total = $cabangModel['total'];
        $perPage = $cabangModel['perPage'];
        $data_cabang = $cabangModel['cabang'];

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari cabang
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_cabang digunakan untuk menampilkan data cabang pada tabel
        *
        * filter_keyword digunakan untuk menampilkan keyword pencarian yang sedang diterapkan
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_cabang' => $data_cabang,
            'filter_keyword' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('cabang/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data cabang baru
    */
    public function add()
    {
        /*
        * Mengirim data-data yang diperlukan pada add cabang
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'data',
            'title' => 'Tambah Data Cabang'
        ];

        return view('cabang/tambah', $data);
    }

    /*
    * Menyimpan data cabang baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add cabang
        */
        $rules = [
            'nama' => [
                'rules' => 'required|is_unique[kantor.nama]',
                'errors' => [
                    'required' => 'Nama cabang harus diisi',
                    'is_unique' => 'Cabang sudah terdaftar',
                ]
            ],
            'alamat' => [
                'rules' => 'required|is_unique[kantor.nama]',
                'errors' => [
                    'required' => 'Alamat cabang harus diisi',
                    'is_unique' => 'Alamat cabang sudah terdaftar',
                ]
            ],
            'kode' => [
                'rules' => 'required|is_unique[kantor.kode_cabang]',
                'errors' => [
                    'required' => 'Kode cabang harus diisi',
                    'is_unique' => 'Kode cabang sudah terdaftar',
                ]
            ]
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add cabang 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('cabang/new'))->withInput();
        }

        /*
        * Membuat Slug dari nama kantor
        * Karena nama kantor Unique, maka sudah pasti nilai slug akan unique
        */
        $newCabangName = $this->request->getVar('nama');
        $originalSlug = url_title($newCabangName, '-', true);

        /*
        * Karena nama kantor bisa mengandung tanda baca yang tidak terdeteksi di url_title, 
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
        * Simpan data ke tabel cabang
        */
        $this->cabangModel->save([
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'alamat' => $this->request->getVar('alamat'),
            'tipe' => $this->tipe_default,
            'kode_cabang' => $this->request->getVar('kode'),
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index cabang
        */
        session()->setFlashdata('berhasil', 'Data cabang baru berhasil ditambahkan');
        return redirect()->to('/cabang');
    }

    /*
    * Membuat slug yang unique
    */
    private function _isSlugExists($slug)
    {
        /*
        * Check apakah slug cabang sudah terdaftar di database
        */
        $cabang = $this->cabangModel->where('slug', $slug)->first();

        /*
        * Kembalikan nilai true jika sudah terdaftar atau false jika belum terdaftar
        */
        return $cabang !== null;
    }

    /*
    * Menampilkan form untuk mengedit data cabang
    */
    public function edit($slug)
    {
        /*
        * Mengambil slug cabang yang ingin diedit
        */
        $slug_cabang = $slug;

        /*
        * Mengambil data cabang berdasarkan slug nya dari database
        */
        $cabang_db = $this->cabangModel->getCabang($slug_cabang)['cabang'];
        $cabang_db = $cabang_db[0];

        /*
        * Jika data cabang tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($cabang_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Cabang ' . $slug_cabang . ' Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan cabang
        * yang ingin diedit, di input fields
        */
        $data = [
            'group' => 'data',
            'title' => 'Edit Data Cabang',
            'kantorName' => $this->kantorName,
            'db' => $cabang_db
        ];

        return view('cabang/edit', $data);
    }

    /*
    * Menyimpan data cabang yang diedit
    */
    public function update()
    {
        /*
        * Mengambil slug yang dikirimkan dari input hidden
        */
        $slug_db = $this->request->getVar('slug_db');

        /*
        * Mengambil data cabang berdasarkan slug nya dari database
        */
        $cabang_db = $this->cabangModel->getCabang($slug_db)['cabang'];
        $cabang_db = $cabang_db[0];

        /*
        * Mengambil data nama, alamat, dan kode untuk unique validation
        */
        $nama_db = $cabang_db['nama'];
        $alamat_db = $cabang_db['alamat'];
        $kode_db = $cabang_db['kode_cabang'];

        /*
        * Mendefinisikan validation rules untuk nama, alamat, dan kode
        * yang unique
        */
        $nama_input = $this->request->getVar('nama');
        if ($nama_db == $nama_input) {
            $rules_nama = 'required';
        } else {
            $rules_nama = 'required|is_unique[kantor.nama]';
        }

        $alamat_input = $this->request->getVar('alamat');
        if ($alamat_db == $alamat_input) {
            $rules_alamat = 'required';
        } else {
            $rules_alamat = 'required|is_unique[kantor.alamat]';
        }

        $kode_input = $this->request->getVar('kode');
        if ($kode_db == $kode_input) {
            $rules_kode = 'required';
        } else {
            $rules_kode = 'required|is_unique[kantor.kode_cabang]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit cabang
        */
        $rules = [
            'nama' => [
                'rules' => $rules_nama,
                'errors' => [
                    'required' => 'Nama cabang harus diisi',
                    'is_unique' => 'Cabang sudah terdaftar',
                ]
            ],
            'alamat' => [
                'rules' => $rules_alamat,
                'errors' => [
                    'required' => 'Alamat cabang harus diisi',
                    'is_unique' => 'Alamat cabang sudah terdaftar',
                ]
            ],
            'kode' => [
                'rules' => $rules_kode,
                'errors' => [
                    'required' => 'Kode cabang harus diisi',
                    'is_unique' => 'Kode cabang sudah terdaftar',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit cabang 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('cabang/edit/' . $cabang_db['slug']))->withInput();
        }

        /*
        * Membuat slug baru jika nama cabang berubah
        */
        $newCabangName = $this->request->getVar('nama');
        $nama_db = $cabang_db['nama'];

        if ($newCabangName != $nama_db) {
            $originalSlug = url_title($newCabangName, '-', true);

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
        * Simpan hasil edit ke tabel cabang
        */
        $this->cabangModel->save([
            'id' => $cabang_db['id'],
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
            'alamat' => $this->request->getVar('alamat'),
            'kode_cabang' => $this->request->getVar('kode'),
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index cabang
        */
        session()->setFlashdata('berhasil', 'Data cabang ' . $cabang_db['nama'] . ' berhasil diedit');
        return redirect()->to('/cabang');
    }

    /*
    * Menghapus data cabang berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus cabang berdasarkan id nya
        */
        $this->cabangModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index cabang
        */
        session()->setFlashdata('berhasil', 'Data Cabang Berhasil Dihapus');
        return redirect()->to('/cabang');
    }
}
