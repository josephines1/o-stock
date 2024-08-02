<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;
use App\Models\KategoriProdukModel;

class KategoriProduk extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $kategoriModel;
    protected $cabangModel;
    protected $usersModel;
    protected $kantorName;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->kategoriModel = new KategoriProdukModel();
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
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_cabang') ? $this->request->getVar('page_cabang') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword') ? $this->request->getGet('keyword') : '',
        ];

        /*
        * Mengambil data kategori yang akan ditampilkan beserta dengan link pagination
        */
        $kategoriModel = $this->kategoriModel->getKategori(false, $filter);
        $pager = $kategoriModel['links'];
        $total = $kategoriModel['total'];
        $perPage = $kategoriModel['perPage'];
        $data_kategori = $kategoriModel['kategori'];

        /*
        * Mengirim data-data yang diperlukan pada view index kategori
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_kategori digunakan untuk menampilkan data kategori pada tabel
        *
        * filter_k digunakan untuk nilai filter yang sedang diterapkan
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'resources',
            'title' => 'Kategori',
            'kantorName' => $this->kantorName,
            'data_kategori' => $data_kategori,
            'filter_k' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('kategori/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchKategori()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getVar('page_kategori') ? $this->request->getVar('page_kategori') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
        ];

        /*
        * Jika keyword tidak terdeteksi, maka anggap saja sebagai string kosong
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';

        /*
        * Mengambil hasil pencarian data kategoti yang akan ditampilkan beserta dengan link pagination
        */
        $kategoriModel = $this->kategoriModel->getKategori(false, $filter);
        $pager = $kategoriModel['links'];
        $total = $kategoriModel['total'];
        $perPage = $kategoriModel['perPage'];
        $data_kategori = $kategoriModel['kategori'];

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari kategori
        *
        * kantorName digunakan pada user title di header
        *
        * data_kategori digunakan untuk menampilkan hasil pencarian data kategori pada tabel
        *
        * filter_keyword digunakan untuk menampilkan filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_kategori' => $data_kategori,
            'filter_keyword' => $filter['keyword'],
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('kategori/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data kategori baru
    */
    public function add()
    {
        /*
        * Mengirim data-data yang diperlukan pada add alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        */
        $data = [
            'group' => 'resources',
            'title' => 'Tambah Data Kategori',
            'kantorName' => $this->kantorName,
        ];

        return view('kategori/tambah', $data);
    }

    /*
    * Menyimpan data kategori baru
    */
    public function store()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add kategori
        */
        $rules = [
            'nama' => [
                'rules' => 'required|is_unique[kategori_produk.nama]',
                'errors' => [
                    'required' => 'Nama kategori harus diisi',
                    'is_unique' => 'Kategori sudah terdaftar',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman add kategori 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('kategori/new'))->withInput();
        }

        /*
        * Membuat Slug dari nama
        */
        $newKategoriName = $this->request->getVar('nama');
        $originalSlug = url_title($newKategoriName, '-', true);

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
        * Simpan data ke tabel kategori_produk
        */
        $this->kategoriModel->save([
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index kategori
        */
        session()->setFlashdata('berhasil', 'Data kategori baru berhasil ditambahkan');
        return redirect()->to('/kategori');
    }

    /*
    * Membuat slug yang unique
    */
    private function _isSlugExists($slug)
    {
        /*
        * Check apakah slug kategori sudah terdaftar di database
        */
        $kategori = $this->kategoriModel->where('slug', $slug)->first();

        /*
        * Kembalikan nilai true jika sudah terdaftar atau false jika belum terdaftar
        */
        return $kategori !== null;
    }

    /*
    * Menampilkan form untuk mengedit data kategori
    */
    public function edit($slug)
    {
        /*
        * Mengambil slug kategori yang ingin diedit
        */
        $slug_kategori = $slug;

        /*
        * Mengambil data kategori berdasarkan slug nya dari database
        */
        $kategori_db = $this->kategoriModel->getKategori($slug_kategori)['kategori'];

        /*
        * Jika tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($kategori_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Kategori ' . $slug_kategori . ' Tidak Ditemukan');
        }

        /*
        * Mengirim data-data yang diperlukan pada halaman edit alokasi
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan kategori
        * yang ingin diedit, di input fields
        */
        $data = [
            'group' => 'resources',
            'title' => 'Edit Data Kategori',
            'kantorName' => $this->kantorName,
            'db' => $kategori_db['0']
        ];

        return view('kategori/edit', $data);
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
        * Mengambil informasi mengenai kategori terkait dari model berdasarkan slug
        */
        $kategori_db = $this->kategoriModel->getKategori($slug_db)['kategori'][0];

        /*
        * Ambil data kategori terkait
        */
        $nama_db = $kategori_db['nama'];

        /*
        * Mendefinisikan validation rules untuk nama yang unique
        */
        $nama_input = $this->request->getVar('nama');
        if ($nama_db == $nama_input) {
            $rules_nama = 'required';
        } else {
            $rules_nama = 'required|is_unique[kategori_produk.nama]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit kategori
        */
        $rules = [
            'nama' => [
                'rules' => $rules_nama,
                'errors' => [
                    'required' => 'Nama kategori harus diisi',
                    'is_unique' => 'Kategori sudah terdaftar',
                ]
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit kategori 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('kategori/edit/' . $kategori_db['slug']))->withInput();
        }

        /*
        * Membuat slug baru jika nama kategori berubah
        */
        $newKategoriName = $this->request->getVar('nama');
        $nama_db = $kategori_db['nama'];

        if ($newKategoriName != $nama_db) {
            $originalSlug = url_title($newKategoriName, '-', true);

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
        * Simpan hasil edit ke tabel kategori_produk
        */
        $this->kategoriModel->save([
            'id' => $kategori_db['id'],
            'nama' => $this->request->getVar('nama'),
            'slug' => $slug,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index kategori
        */
        session()->setFlashdata('berhasil', 'Data kategori ' . $kategori_db['nama'] . ' berhasil diedit');
        return redirect()->to('/kategori');
    }

    /*
    * Menghapus data kategori berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus cabang berdasarkan id nya
        */
        $this->kategoriModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index kategori
        */
        session()->setFlashdata('berhasil', 'Data Kategori Berhasil Dihapus');
        return redirect()->to('/kategori');
    }
}
