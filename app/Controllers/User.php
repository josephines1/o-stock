<?php

namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\UsersModel;
use App\Models\CabangModel;
use App\Models\UsersRoleModel;
use Myth\Auth\Controllers\AuthController;

class User extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $roleModel;
    protected $usersModel;
    protected $photo_default;
    protected $usersRoleModel;
    protected $cabangModel;
    protected $kantorName;
    protected $auth;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->roleModel = new RoleModel();
        $this->usersModel = new UsersModel();
        $this->photo_default = 'user.png';
        $this->usersRoleModel = new UsersRoleModel();
        $this->cabangModel = new CabangModel();
        $this->auth = new AuthController();

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
        $currentPage = $this->request->getGet('page_user') ? $this->request->getGet('page_user') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        */
        $filter = [
            'keyword' => $this->request->getPost('keyword') ? $this->request->getPost('keyword') : '',
            'role' => $this->request->getPost('role') ? $this->request->getPost('role') : 0,
            'cabang' => $this->request->getPost('cabang') ? $this->request->getPost('cabang') : 0,
        ];

        /*
        * Menentukan sebutan filter untuk role
        */
        if ($filter['role'] != 0) {
            $filter_role = $this->roleModel->find($filter['role']);
            $filter_role_nama = $filter_role['name'];
        } else {
            $filter_role_nama = "Semua Role";
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
        * Mengambil data yang akan ditampilkan
        * beserta dengan link pagination
        */
        $usersModel = $this->usersModel->getUsers(false, $filter);
        $pager = $usersModel['links'];
        $total = $usersModel['total'];
        $perPage = $usersModel['perPage'];
        $data_users = $usersModel['users'];

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * data_users digunakan untuk menampilkan data users pada tabel
        *
        * data_role dan data_cabang digunakan untuk menampilkan pilihan filter
        *
        * filter_k, filter_r, dan filter_c digunakan pada nilai filter 
        * yang sedang diterapkan
        *
        * filter_cabang dan filter_role digunakan pada sebutan
        * filter yang sedang diterapkan, di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'group' => 'data',
            'title' => 'User',
            'kantorName' => $this->kantorName,
            'data_users' => $data_users,
            'data_role' => $this->roleModel->findAll(),
            'data_cabang' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'filter_k' => $filter['keyword'],
            'filter_r' => $filter['role'],
            'filter_c' => $filter['cabang'],
            'filter_cabang' => $filter_cabang_nama,
            'filter_role' => $filter_role_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('user/index', $data);
    }

    /*
    * Menampilkan hasil pencarian
    */
    public function searchUser()
    {
        /*
        * Mengambil nilai halaman saat ini untuk pagination
        */
        $currentPage = $this->request->getGet('page_user') ? $this->request->getGet('page_user') : 1;

        /*
        * Mendefinisikan filter-filter yang dibutuhkan
        * User dengan role cabang hanya akan melihat data untuk cabang mereka
        * Filter keyword digunakan untuk pencarian
        */
        $filter = [
            'keyword' => $this->request->getGet('keyword'),
            'role' => $this->request->getGet('role'),
            'cabang' => $this->request->getGet('cabang') ? $this->request->getGet('cabang') : 0,
        ];

        /*
        * Jika ada filter yang terdeteksi null, maka anggap saja tampilkan semua data
        */
        $filter['keyword'] = $filter['keyword'] ? $filter['keyword'] : '';
        $filter['role'] = $filter['role'] ? $filter['role'] : 0;
        $filter['cabang'] = $filter['cabang'] ? $filter['cabang'] : 0;

        /*
        * Menentukan sebutan filter untuk role
        */
        if ($filter['role'] != 0) {
            $filter_role = $this->roleModel->find($filter['role']);
            $filter_role_nama = $filter_role['name'];
        } else {
            $filter_role_nama = "Semua Role";
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
        * Mengambil hasil pencarian yang akan ditampilkan
        * beserta dengan link pagination
        */
        $usersModel = $this->usersModel->getUsers(false, $filter);
        $pager = $usersModel['links'];
        $total = $usersModel['total'];
        $perPage = $usersModel['perPage'];
        $data_users = $usersModel['users'];
        $data_role = $this->roleModel->findAll();

        /*
        * Mengirim data-data yang diperlukan pada view hasil cari alokasi
        *
        * kantorName digunakan pada user title di header
        *
        * data_users digunakan untuk menampilkan hasil pencarian data users pada tabel
        *
        * filter_role, filter_keyword, dan filter_cabang digunakan untuk menampilkan filter yang sedang aktif di dalam tabel
        *
        * currentPage, pager, total, dan perPage digunakan link pagination
        */
        $data = [
            'kantorName' => $this->kantorName,
            'data_users' => $data_users,
            'role' => $data_role,
            'filter_role' => $filter_role_nama,
            'filter_keyword' => $filter['keyword'],
            'filter_cabang' => $filter_cabang_nama,
            'currentPage' => $currentPage,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $perPage,
        ];

        return view('user/hasil-cari', $data);
    }

    /*
    * Menampilkan form untuk menambah data baru
    */
    public function add()
    {
        /*
        * Mendefinisikan role
        */
        $cabang_role = 'cabang';
        $cabang_roleModel = $this->roleModel->where('name', $cabang_role)->first();
        $cabang_role_id = $cabang_roleModel['id'];

        /*
        * Mengirim data-data yang diperlukan pada add
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * data_role digunakan pada opsi select role
        * cabang_options digunakan pada opsi select cabang
        *
        * cabang_role_id digunakan untuk memeriksa, jika input role nya
        * cabang, maka harus pilih cabang mana di view
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'data',
            'title' => 'Tambah Data User',
            'data_role' => $this->roleModel->findAll(),
            'cabang_options' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'cabang_role_id' => $cabang_role_id,
        ];

        return view('user/tambah', $data);
    }

    /*
    * Menyimpan data baru
    */
    public function store()
    {
        /*
        * Mengambil id role pusat
        */
        $pusat_roleModel = $this->roleModel->where('name', 'pusat')->first();
        $pusat_role_id = $pusat_roleModel['id'];

        /*
        * Mengambil id kantor pusat
        */
        $pusat_cabangModel = $this->cabangModel->where('tipe', 'pusat')->first();
        $pusat_cabang_id = $pusat_cabangModel['id']; // id kantor pusat

        // Jika user memilih role 'pusat', maka arahkan id kantor pusat
        if ($this->request->getVar('role') == $pusat_role_id) {
            $id_kantor = $pusat_cabang_id;
            $rules_cabang = 'not_in_list[0]';
        } else {
            $id_kantor = $this->request->getVar('cabang');
            $rules_cabang = 'required|numeric';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form add
        */
        $rules = [
            'username' => [
                'rules' => 'required|alpha_numeric|min_length[5]|max_length[30]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'is_unique' => 'Username sudah terdaftar',
                    'min_length' => 'Username harus terdiri dari 5-30 karakter',
                    'max_length' => 'Username harus terdiri dari 5-30 karakter',
                    'alpha_numeric' => 'Username hanya terdiri dari kombinasi "alfabet" dan "angka" tanpa spasi',
                ],
            ],
            'fullname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama lengkap user harus diisi',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Alamat email harus diisi',
                    'valid_email' => 'Alamat email tidak valid',
                    'is_unique' => 'Alamat email sudah terdaftar',
                ]
            ],
            'role' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Role harus diisi',
                    'numeric' => 'Pilih role yang tersedia',
                ]
            ],
            'cabang' => [
                'rules' => $rules_cabang,
                'errors' => [
                    'required' => 'Cabang untuk user harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
                    'not_in_list' => 'Cabang tidak valid.'
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
            return redirect()->to(base_url('/user/new'))->withInput();
        }

        /*
        * Mendefinisikan password default dan melakukan hash
        */
        $password_default = 'password';
        $password_default_hash = $this->usersModel->hashPassword($password_default);

        /*
        * Simpan data ke tabel users
        */
        $this->usersModel->save([
            'email' => $this->request->getVar('email'),
            'username' => $this->request->getVar('username'),
            'fullname' => $this->request->getVar('fullname'),
            'photo_profile' => $this->photo_default,
            'password_hash' => $password_default_hash,
            'active' => 1,
            'id_kantor' => $id_kantor,
        ]);

        // Mendapatkan ID terakhir dari model user
        $user_id = $this->usersModel->insertID();

        /*
        * Daftarkan user ke role terkait
        */
        $this->usersRoleModel->save([
            'group_id' => $this->request->getVar('role'),
            'user_id' => $user_id,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index kategori
        */
        session()->setFlashdata('berhasil', 'Data user berhasil ditambahkan');
        return redirect()->to('/user');
    }

    /*
    * Menampilkan form untuk mengedit data
    */
    public function edit($username)
    {
        /*
        * Mendefinisikan role
        */
        $cabang_role = 'cabang';
        $cabang_roleModel = $this->roleModel->where('name', $cabang_role)->first();
        $cabang_role_id = $cabang_roleModel['id'];

        /*
        * Mengambil data user berdasarkan username
        */
        $user_db = $this->usersModel->getUsers($username)['users'];

        /*
        * Jika tidak ditemukan, tampilkan 404 NOT FOUND
        */
        if (empty($user_db)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data User ' . $username . ' Tidak Ditemukan');
        }

        /*
        * Mengambil id role user
        */
        $user_id_db = $user_db[0]['id'];
        $userRoleModel = $this->usersRoleModel->where('user_id', $user_id_db)->first();
        $user_role_id = $userRoleModel['group_id'];

        /*
        * Mengirim data-data yang diperlukan pada halaman edit
        * 
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * 
        * db digunakan untuk menampilkan produk
        * yang ingin diedit, di input fields
        *
        * data_role digunakan pada opsi select role
        * cabang_options digunakan pada opsi select cabang
        *
        * user_role_id digunakan untuk menampilkan role user,
        * 
        * cabang_role_id digunakan untuk memeriksa, jika input role nya
        * cabang, maka harus pilih cabang mana di view
        */
        $data = [
            'group' => 'data',
            'title' => 'Edit Data User',
            'kantorName' => $this->kantorName,
            'db' => $user_db['0'],
            'data_role' => $this->roleModel->findAll(),
            'cabang_options' => $this->cabangModel->where('tipe', 'cabang')->get()->getResultArray(),
            'user_role_id' => $user_role_id,
            'cabang_role_id' => $cabang_role_id,
        ];

        return view('user/edit', $data);
    }

    /*
    * Menyimpan data yang diedit
    */
    public function update()
    {
        /*
        * Mengambil id role pusat
        */
        $pusat_roleModel = $this->roleModel->where('name', 'pusat')->first();
        $pusat_role_id = $pusat_roleModel['id']; // id role pusat

        /*
        * Mengambil id kantor pusat
        */
        $pusat_cabangModel = $this->cabangModel->where('tipe', 'pusat')->first();
        $pusat_cabang_id = $pusat_cabangModel['id']; // id kantor pusat

        // Jika user memilih role 'pusat', maka arahkan id kantor pusat
        if ($this->request->getVar('role') == $pusat_role_id) {
            $id_kantor = $pusat_cabang_id;
            $rules_cabang = 'not_in_list[0]';
        } else {
            $id_kantor = $this->request->getVar('cabang');
            $rules_cabang = 'required|numeric';
        }

        /*
        * Mengambil username dari input hidden
        */
        $username_db = $this->request->getVar('username_db');

        /*
        * Mengambil informasi mengenai data terkait dari model berdasarkan username
        */
        $user_db = $this->usersModel->getUsers($username_db)['users'][0];

        /*
        * Ambil data email user terkait
        */
        $email_db = $user_db['email'];

        /*
        * Mendefinisikan validation rules untuk email dan username yang unique
        */
        $email_input = $this->request->getVar('email');
        if ($email_db == $email_input) {
            $rules_email = 'required|valid_email';
        } else {
            $rules_email = 'required|valid_email|is_unique[users.email]';
        }

        $username_input = $this->request->getVar('username');
        if ($username_db == $username_input) {
            $rules_username = 'required|alpha_numeric|min_length[5]|max_length[30]';
        } else {
            $rules_username = 'required|alpha_numeric|min_length[5]|max_length[30]|is_unique[users.username]';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit
        */
        $rules = [
            'username' => [
                'rules' => $rules_username,
                'errors' => [
                    'required' => 'Username harus diisi',
                    'is_unique' => 'Username sudah terdaftar',
                    'min_length' => 'Username harus terdiri dari 5-30 karakter',
                    'max_length' => 'Username harus terdiri dari 5-30 karakter',
                    'alpha_numeric' => 'Username hanya terdiri dari kombinasi "alfabet" dan "angka" tanpa spasi',
                ],
            ],
            'email' => [
                'rules' => $rules_email,
                'errors' => [
                    'required' => 'Alamat email harus diisi',
                    'valid_email' => 'Alamat email tidak valid',
                    'is_unique' => 'Alamat email sudah terdaftar',
                ]
            ],
            'fullname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama lengkap user harus diisi',
                ]
            ],
            'status' => [
                'rules' => 'required|numeric|in_list[0,1]',
                'errors' => [
                    'required' => 'Status harus diisi.',
                    'numeric' => 'Pilih status yang tersedia',
                ]
            ],
            'role' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Role harus diisi.',
                    'numeric' => 'Pilih role yang tersedia',
                ]
            ],
            'cabang' => [
                'rules' => $rules_cabang,
                'errors' => [
                    'required' => 'Cabang untuk user harus diisi',
                    'numeric' => 'Pilih cabang yang tersedia',
                    'not_in_list' => 'Cabang tidak valid.'
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
            return redirect()->to(base_url('user/edit/' . $user_db['username']))->withInput();
        }

        /*
        * Cek apakah akun user di nonaktifkan
        * Jika iya, generate activate_hash agar bisa aktivasi di kemudian hari
        */
        if ($this->request->getPost('status') == 0) {
            $activate_hash = bin2hex(random_bytes(16));
        } else {
            $activate_hash = null;
        }

        /*
        * Simpan hasil edit ke tabel users
        */
        $this->usersModel->save([
            'id' => $user_db['id'],
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'active' => $this->request->getPost('status'),
            'activate_hash' => $activate_hash,
            'fullname' => $this->request->getPost('fullname'),
            'id_kantor' => $id_kantor,
        ]);

        /*
        * Ambil role dan id role dari form edit
        */
        $role = $this->request->getVar('role');
        $role_id_db = $this->request->getVar('role_id_db');

        /*
        * Ambil id user yang diedit
        */
        $user_id = $user_db['id'];

        /*
        * Jika ada perubahan, maka lakukan addUserToGroup dan removeUserFromGroup
        */
        if ($role !== $role_id_db) {
            // Mendapatkan instance model Group milik myth/auth
            $groupModel = new \Myth\Auth\Models\GroupModel();

            $groupModel->addUserToGroup($user_id, (int)$role);
            $groupModel->removeUserFromGroup($user_id, (int)$role_id_db);
        }

        /*
        * Ambil fullname user, jika tidak ada, ambil username
        */
        $nama_user = $user_db['fullname'] ? $user_db['fullname'] : $user_db['username'];

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data user ' . $nama_user . ' berhasil diedit');
        return redirect()->to('/user');
    }

    /*
    * Menghapus data photo user
    */
    public function hapusPhoto()
    {
        // Ambil id user dan photo user dari input hidden di view
        $user_id = $this->request->getPost('user_id');
        $photo_db = $this->request->getPost('photo_db');

        // Ambil data user dari database berdasarkan id nya
        $data_user = $this->usersModel->find($user_id);

        // Jika photonya bukan photo default, maka hapus photo lama nya
        // dan simpan photo baru nya ke database
        if ($photo_db != $this->photo_default) {
            $this->usersModel->save([
                'id' => $user_id,
                'photo_profile' => $this->photo_default,
            ]);

            unlink('assets/img/user_profile/' . $photo_db);
        }

        /*
        * Ambil fullname user, jika tidak ada, ambil username
        */
        $nama_user = $data_user['fullname'] ? $data_user['fullname'] : $data_user['username'];

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Foto User ' . $nama_user . ' berhasil dihapus');
        return redirect()->to(base_url('/user/edit/' . $data_user['username']));
    }

    /*
    * Menghapus data berdasarkan id
    */
    public function delete($id)
    {
        /*
        * Hapus data berdasarkan id nya
        */
        $this->usersModel->delete($id);

        /*
        * Atur pesan berhasil dan kembali ke halaman index
        */
        session()->setFlashdata('berhasil', 'Data User Berhasil Dihapus');
        return redirect()->to('/user');
    }
}
