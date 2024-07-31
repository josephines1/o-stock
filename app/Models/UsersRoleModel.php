<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersRoleModel extends Model
{
    // Nama tabel yang digunakan dalam model ini
    protected $table = 'auth_groups_users';

    // Kolom yang dapat diisi (allowed fields)
    protected $allowedFields = ['group_id', 'user_id'];

    /**
     * Mengambil data role pengguna berdasarkan user_id
     *
     * @param int|false $user_id ID pengguna. Jika false, ambil semua data
     * @return array|object Daftar data role pengguna atau data role pengguna tertentu
     */
    public function getUsersRole($user_id = false)
    {
        // Jika user_id diberikan, cari dan kembalikan data role untuk pengguna tersebut
        if ($user_id) {
            return $this->find($user_id);
        } else {
            // Jika user_id tidak diberikan, kembalikan semua data role pengguna
            return $this->findAll();
        }
    }
}
