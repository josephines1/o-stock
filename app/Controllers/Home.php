<?php

namespace App\Controllers;

use App\Models\CabangModel;
use App\Models\KonsumenModel;
use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use App\Models\UsersModel;

class Home extends BaseController
{
    /*
    * Mendeklarasi model-model untuk database yang diperlukan di halaman Home
    */
    protected $cabangModel;
    protected $konsumenModel;
    protected $produkModel;
    protected $penjualanModel;
    protected $usersModel;

    public function __construct()
    {
        /*
        * Menginisiasi model-model untuk database yang diperlukan di halaman Home
        */
        $this->cabangModel = new CabangModel();
        $this->konsumenModel = new KonsumenModel();
        $this->produkModel = new ProdukModel();
        $this->penjualanModel = new PenjualanModel();
        $this->usersModel = new UsersModel();
    }

    /*
    * Menampilkan halaman utama website (Home)
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
        $user_login_kantor = $this->cabangModel->find($user_login_kantor_id);

        /*
        * Menginisiasi data penjualan 7 hari terakhir untuk ditampilkan di dalam Chart
        * Data diambil dari penjualanModel, pada method getPenjualanLastSevenDays
        * Pada method diterapkan filter berupa role dan kantor untuk menyesuaikan data yang ditampilkan
        */
        $chartDate = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date("Y-m-d", strtotime("-$i days"));
            $chartDate[] = $date;
        }
        $penjualanLastSevenDays = $this->penjualanModel->getPenjualanLastSevenDays($user_login_role, $user_login_kantor_id);

        /*
        * Menginisiasi data penjualan 12 bulan terakhir untuk ditampilkan di dalam Chart
        * Data diambil dari penjualanModel, pada method getPenjualanLastTwelveMonths
        * Pada method diterapkan filter berupa role dan kantor untuk menyesuaikan data yang ditampilkan
        */
        $chartMonths = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date("Y-m", strtotime("first day of -$i months"));
            $chartMonths[] = $month;
        }
        $penjualanEachMonths = $this->penjualanModel->getPenjualanLastTwelveMonths($user_login_role, $user_login_kantor_id);

        /*
        * Mengirim data-data yang diperlukan pada view home
        * group & title digunakan pada status active navbar
        * kantorName digunakan pada user title di header
        * totalCabang, totalKonsumen, totalProduk, dan totalPenjualan digunakan pada cards
        * chartDate dan chartMonths digunakan pada Label Charts
        * penjualanLastSevenDays dan penjualanEachMonths digunakan pada Data Charts
        */
        $data = [
            'group' => 'home',
            'title' => 'Home',
            'kantorName' => $user_login_kantor['nama'],
            'totalCabang' => $this->cabangModel->getTotalCabang(),
            'totalKonsumen' => $this->konsumenModel->getTotalKonsumen($user_login_role, $user_login_kantor_id),
            'totalProduk' => $this->produkModel->getTotalProduk(),
            'totalPenjualan' => $this->penjualanModel->getTotalPenjualan($user_login_role, $user_login_kantor_id),
            'chartDate' => $chartDate,
            'chartMonths' => $chartMonths,
            'penjualanLastSevenDays' => $penjualanLastSevenDays,
            'penjualanEachMonths' => $penjualanEachMonths,
        ];

        return view('home/index', $data);
    }
}
