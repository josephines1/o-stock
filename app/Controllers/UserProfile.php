<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\CabangModel;

class UserProfile extends BaseController
{
    /*
    * Mendeklarasi model untuk database dan properti yang diperlukan
    */
    protected $usersModel;
    protected $cabangModel;
    protected $kantorName;
    protected $photo_default;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan
        */
        $this->usersModel = new UsersModel();
        $this->cabangModel = new CabangModel();
        $this->photo_default = 'user.png';

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
    * Menampilkan data di halaman profile
    */
    public function index(): string
    {
        // Mengambil data user dari database
        $data_user = $this->usersModel->getUsers(user()->username)['users'][0];

        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        *
        * user digunakan untuk menampilkan data user 
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'profile',
            'title' => 'User Profile',
            'user' => $data_user,
        ];

        return view('profile/index', $data);
    }

    /*
    * Menampilkan form untuk mengedit profile
    */
    public function edit()
    {
        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'profile',
            'title' => 'Edit Profile Anda',
        ];

        return view('profile/edit', $data);
    }

    /*
    * Menyimpan profile yang diedit
    */
    public function update()
    {
        // Ambil username di database dan di input 
        $username_db = user()->username;
        $username_edit = $this->request->getPost('username');

        /*
        * Mendefinisikan validation rules untuk username dan email yang unique
        */
        if ($username_edit !== $username_db) {
            $rules_username = 'required|is_unique[users.username]|max_length[30]|min_length[3]';
        } else {
            $rules_username = 'required|max_length[30]|min_length[3]';
        }

        $email_db = user()->email;
        $email_edit = $this->request->getPost('email');

        if ($email_edit !== $email_db) {
            $rules_email = 'required|is_unique[users.email]|valid_email';
        } else {
            $rules_email = 'required|valid_email';
        }

        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit profile
        */
        $rules = [
            'foto' => [
                'rules' => 'max_size[foto,2048]|mime_in[foto,image/png,image/jpeg,image/jpg]|ext_in[foto,png,jpg,jpeg]|is_image[foto]',
                'errors' => [
                    'max_size' => 'upload foto dengan ukuran maksimal 2MB',
                    'mime_in' => 'upload foto dengan ekstensi png, jpeg, atau jpg',
                    'ext_in' => 'upload foto dengan ekstensi png, jpeg, atau jpg',
                    'is_image' => 'upload foto dengan ekstensi png, jpeg, atau jpg',
                ]
            ],
            'username' => [
                'rules' => $rules_username,
                'errors' => [
                    'required' => 'Username harus diisi',
                    'is_unique' => 'Username sudah terdaftar.',
                    'max_length' => 'Isi username dengan 3-30 karakter',
                    'min_length' => 'Isi username dengan 3-30 karakter',
                ]
            ],
            'fullname' => [
                'rules' => 'required|max_length[50]|min_length[3]',
                'errors' => [
                    'required' => 'fullname harus diisi',
                    'max_length' => 'Isi fullname dengan 3-50 karakter',
                    'min_length' => 'Isi fullname dengan 3-50 karakter',
                ]
            ],
            'email' => [
                'rules' => $rules_email,
                'errors' => [
                    'required' => 'Alamat Email wajib diisi',
                    'valid_email' => 'Alamat email tidak valid',
                    'is_unique' => 'Alamat email sudah terdaftar'
                ]
            ]
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit profile 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to('/profile/edit')->withInput();
        }

        // Mengambil file foto profile
        $file_foto = $this->request->getFile('foto');

        if ($file_foto->getError() == 4) {
            // Jika tidak ada file photo yang diupload, maka gunakan photo lama
            $nama_foto = $this->request->getPost('oldFoto');
        } else {
            // Jika ada upload photo baru, maka generate nama untuk file photo yang baru
            // dan simpan ke folder user_profile di folder public
            $nama_foto = 'FotoProfile-' . user()->username . '-' . date('Y-m-d-His') . '.jpg';
            $file_foto->move(FCPATH . 'assets/img/user_profile/', $nama_foto);

            // Jika photo lama nya bukan photo default, maka hapus photo lama nya
            if ($this->request->getPost('oldFoto') !== $this->photo_default) {
                unlink('assets/img/user_profile/' . $this->request->getPost('oldFoto'));
            }
        }

        // Simpan hasil edit ke tabel users
        $this->usersModel->save([
            'id' => user_id(),
            'username' => $this->request->getPost('username'),
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'photo_profile' => $nama_foto
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman index produk
        */
        session()->setFlashdata('berhasil', 'Profile Anda berhasil diupdate.');
        return redirect()->to('/profile');
    }

    /*
    * Menghapus data photo user
    */
    public function hapusFoto()
    {
        // Ambil photo lama dari database
        $foto_db = user()->photo_profile;

        // Jika photo lama berbeda dengan photo baru, maka simpan photo yang baru
        // dan hapus photo yang lama
        if ($foto_db !== $this->photo_default) {
            $this->usersModel->save([
                'id' => user_id(),
                'photo_profile' => $this->photo_default,
            ]);

            unlink('assets/img/user_profile/' . $foto_db);
        }

        /*
        * Atur pesan berhasil dan kembali ke halaman profile
        */
        session()->setFlashdata('berhasil', 'Foto berhasil dihapus');
        return redirect()->to(base_url('/profile/edit'));
    }

    /*
    * Mengubah password user
    */
    public function ubahPassword()
    {
        /*
        * Mengirim data-data yang diperlukan pada view index alokasi
        *
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        */
        $data = [
            'kantorName' => $this->kantorName,
            'group' => 'profile',
            'title' => 'Ubah Password Anda',
        ];

        return view('profile/ubah-password', $data);
    }

    /*
    * Simpan password baru user
    */
    public function simpanPassword()
    {
        /*
        * Mendefinisikan rules untuk melakukan validasi
        * pada field-field yang ada di form edit password
        */
        $rules = [
            'current_pass' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Masukkan password Anda saat ini.'
                ],
            ],
            'new_pass' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Masukkan password baru Anda.',
                    'min_length' => 'Password minimal 6 karakter.'
                ],
            ],
            'conf_pass' => [
                'rules' => 'required|matches[new_pass]',
                'errors' => [
                    'required' => 'Konfirmasi password baru tidak sesuai.',
                    'matches' => 'Konfirmasi password baru tidak sesuai.',
                ],
            ],
        ];

        /*
        * Melakukan Validasi Dasar dari rules yang telah didefinisikan di atas
        * 
        * Jika ada data yang tidak valid, maka kembali ke halaman edit password 
        * dengan menampilkan pesan error
        */
        if (!$this->validate($rules)) {
            return redirect()->to(base_url('profile/ubah-password'))->withInput();
        }

        /*
        * Ambil hash password user dari database
        */
        $db_hash_pass = user()->password_hash;

        /*
        * Verifikasi password lama dari database dengan input dari user
        */
        if (!password_verify(base64_encode(hash('sha384', service('request')->getPost('current_pass'), true)), $db_hash_pass)) {
            session()->setFlashdata('password-salah', 'Oops. Cek kembali password Anda.');
            return redirect()->to(base_url('profile/ubah-password'));
        }

        /*
        * Buat hash untuk password baru
        */
        $new_hash_pass = password_hash(base64_encode(hash('sha384', service('request')->getPost('new_pass'), true)), PASSWORD_DEFAULT);

        /*
        * Simpan password ke tabel users
        */
        $this->usersModel->save([
            'id' => user_id(),
            'password_hash' => $new_hash_pass,
        ]);

        /*
        * Atur pesan berhasil dan kembali ke halaman profile
        */
        session()->setFlashdata('berhasil', 'Berhasil Ubah Password!');
        return redirect()->to('/profile');
    }
}
