<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'auth_groups'; // Nama tabel yang digunakan
    protected $primaryKey = 'id'; // Kunci utama tabel
    protected $allowedFields = ['name', 'description']; // Kolom yang dapat diisi
    protected $useTimestamps = true; // Menggunakan timestamps untuk kolom created_at dan updated_at
}
