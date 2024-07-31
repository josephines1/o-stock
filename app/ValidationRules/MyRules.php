<?php

namespace App\ValidationRules;

use App\Models\KartuStokModel;

class MyRules
{
    /*
    * Untuk Cek Produk yang Di-input di Penjualan tidak ada yang Duplikat.
    */
    public static function unique_value(array $data): array
    {
        // Mendapatkan nilai produk yang di-input dari array data
        $produkValues = $data['produk'];

        // Inisialisasi array untuk menyimpan pesan error
        $messages = [];

        // Menghitung jumlah kemunculan setiap produk
        $count = array_count_values($produkValues);

        // Mengecek produk yang muncul lebih dari sekali
        foreach ($count as $produk => $jumlah) {
            if ($jumlah > 1) {
                // Mendapatkan indeks produk yang duplikat
                foreach (array_keys($produkValues, $produk) as $index) {
                    // Menambahkan pesan error pada indeks produk yang duplikat
                    $messages[$index] = "Produk duplikat";
                }
            }
        }

        // Mengembalikan array pesan error
        return $messages;
    }

    /*
    * Untuk Cek Produk yang Di-input di Penjualan tidak melebihi stok yang tercatat.
    */
    public static function check_stock(array $data): array
    {
        // Mendapatkan nilai produk dan jumlah yang di-input dari array data
        $produkValues = $data['produk'];
        $jumlahValues = $data['jumlah'];

        // Mendapatkan tanggal hari ini dalam format 'Y-m-d'
        $tanggal = date('Y-m-d');

        // Inisialisasi array untuk menyimpan pesan error
        $messages = [];

        // Mengecek stok setiap produk
        foreach ($produkValues as $index => $p) {
            // Mendapatkan stok akhir terbaru untuk produk berdasarkan tanggal
            $stock = (new KartuStokModel())->getLatestStokAkhir($p, $tanggal) ?? 0;

            // Jika jumlah yang di-input melebihi stok yang tersedia, tambahkan pesan error
            if (isset($jumlahValues[$index]) && $jumlahValues[$index] > $stock) {
                $messages[$index] = "Stok yang tersedia: " . $stock;
            }
        }

        // Mengembalikan array pesan error
        return $messages;
    }
}
