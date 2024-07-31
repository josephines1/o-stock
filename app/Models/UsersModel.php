<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    // Nama tabel yang digunakan oleh model ini
    protected $table = 'users';

    // Nama primary key dari tabel
    protected $primaryKey = 'id';

    // Field yang diizinkan untuk diisi
    protected $allowedFields = [
        'email',
        'username',
        'fullname',
        'photo_profile',
        'id_kantor',
        'password_hash',
        'active',
        'reset_at',
    ];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;

    /*
    * Mengambil data user berdasarkan username, filter.
    */
    public function getUsers($username = false, $filter = false, $perPage = 10)
    {
        // Mendapatkan layanan pager dari CodeIgniter 4
        $pager = service('pager');

        // Menetapkan path dasar untuk pager
        $pager->setPath('user', 'user');

        // Mendapatkan halaman saat ini dari query string atau default ke 1
        $page = (@$_GET['page_user']) ? $_GET['page_user'] : 1;
        // Menghitung offset berdasarkan halaman saat ini dan jumlah item per halaman
        $offset = ($page - 1) * $perPage;

        // Menghubungkan ke database dan mendapatkan builder untuk tabel users
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        // Menentukan kolom yang akan dipilih dan melakukan join dengan tabel terkait
        $builder->select('
            users.*, 
            users.id as userid, 
            auth_groups.name as role,
            auth_groups.id as role_id,
            kantor.id as kantor_id,
            kantor.nama as nama_kantor,
            kantor.slug as slug_kantor,
            kantor.kode_cabang as kode_kantor
        ');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->join('kantor', 'kantor.id = users.id_kantor');

        // Inisialisasi total hasil
        $total = 0;

        // Membuat clone dari builder untuk menghitung total hasil
        $countQuery = clone $builder;

        // Menghitung total hasil tanpa filter
        $total = $countQuery->countAllResults();

        // Jika username diberikan, menghitung total hasil berdasarkan username
        if ($username) {
            $countQuery = clone $builder;
            $total = $countQuery->where('username', $username)->countAllResults();
        } else if ($filter) {
            // Jika filter diberikan, menerapkan filter pada query
            $filter_keyword = $filter['keyword'];
            $filter_role = $filter['role'];
            $filter_cabang = $filter['cabang'];

            if ($filter_keyword) {
                $builder->groupStart()
                    ->like('users.username', $filter_keyword)
                    ->orLike('users.fullname', $filter_keyword)
                    ->orLike('users.email', $filter_keyword)
                    ->groupEnd();
            }

            if ($filter_role != 0) {
                $builder->groupStart()
                    ->where('auth_groups.id', $filter_role)
                    ->groupEnd();
            }

            if ($filter_cabang != 0) {
                $builder->groupStart()
                    ->where('users.id_kantor', $filter_cabang)
                    ->groupEnd();
            }

            $countQuery = clone $builder;
            $total = $countQuery->countAllResults();
        }

        if ($username) {
            // Jika username diberikan, mendapatkan hasil berdasarkan username dengan paging
            $result = $builder->where('username', $username)->get($perPage, $offset)->getResultArray();
        } else {
            // Mendapatkan hasil dengan paging
            $result = $builder->get($perPage, $offset)->getResultArray();
        }

        // Mengembalikan array yang berisi hasil users, link pager, total hasil, jumlah item per halaman, dan halaman saat ini
        return [
            'users' => $result,
            'links' => $pager->makeLinks($page, $perPage, $total, 'my_pagination', 0, 'user'),
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
        ];
    }

    /*
    * Melakukan proses hash password
    */
    public function hashPassword($password_string)
    {
        // Menghasilkan hash dari string password menggunakan algoritma sha384 dan mengkodekannya ke base64, 
        // kemudian menghashnya menggunakan password_hash
        return password_hash(base64_encode(hash('sha384', $password_string, true)), PASSWORD_DEFAULT);
    }
}
