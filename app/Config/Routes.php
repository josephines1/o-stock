<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');

// Salesman
$routes->get('/salesman', 'Salesman::index');
$routes->get('/salesman/new', 'Salesman::add');
$routes->post('/salesman/store', 'Salesman::store');
$routes->get('/cari-salesman', 'Salesman::searchSalesman');
$routes->get('/salesman/edit/(:segment)', 'Salesman::edit/$1', ['filter' => 'role:pusat']);
$routes->post('/salesman/update', 'Salesman::update/$1', ['filter' => 'role:pusat']);
$routes->delete('/salesman/(:num)', 'Salesman::delete/$1', ['filter' => 'role:pusat']);

// Cabang
$routes->get('/cabang', 'Cabang::index', ['filter' => 'role:pusat']);
$routes->get('/cabang/new', 'Cabang::add', ['filter' => 'role:pusat']);
$routes->post('/cabang/store', 'Cabang::store', ['filter' => 'role:pusat']);
$routes->get('/cabang/edit/(:segment)', 'Cabang::edit/$1', ['filter' => 'role:pusat']);
$routes->post('/cabang/update', 'Cabang::update', ['filter' => 'role:pusat']);
$routes->delete('/cabang/(:num)', 'Cabang::delete/$1', ['filter' => 'role:pusat']);
$routes->get('/cari-cabang', 'Cabang::searchCabang', ['filter' => 'role:pusat']);

// Konsumen
$routes->get('/konsumen', 'Konsumen::index');
$routes->get('/konsumen/new', 'Konsumen::add');
$routes->post('/konsumen/store', 'Konsumen::store');
$routes->get('/cari-konsumen', 'Konsumen::searchKonsumen');
$routes->get('/konsumen/edit/(:segment)', 'Konsumen::edit/$1');
$routes->post('/konsumen/update', 'Konsumen::update/$1');
$routes->delete('/konsumen/(:num)', 'Konsumen::delete/$1', ['filter' => 'role:pusat']);

// Kelola User
$routes->get('/user', 'User::index', ['filter' => 'role:pusat']);
$routes->get('/user/new', 'User::add', ['filter' => 'role:pusat']);
$routes->post('/user/store', 'User::store', ['filter' => 'role:pusat']);
$routes->get('/user/edit/(:segment)', 'User::edit/$1', ['filter' => 'role:pusat']);
$routes->post('/hapus-photo', 'User::hapusPhoto', ['filter' => 'role:pusat']);
$routes->post('/user/update', 'User::update', ['filter' => 'role:pusat']);
$routes->delete('/user/(:num)', 'User::delete/$1', ['filter' => 'role:pusat']);
$routes->get('/cari-user', 'User::searchUser', ['filter' => 'role:pusat']);

// Kategori Produk
$routes->get('/kategori', 'KategoriProduk::index');
$routes->get('/kategori/new', 'KategoriProduk::add', ['filter' => 'role:pusat']);
$routes->post('/kategori/store', 'KategoriProduk::store', ['filter' => 'role:pusat']);
$routes->get('/kategori/edit/(:segment)', 'KategoriProduk::edit/$1', ['filter' => 'role:pusat']);
$routes->post('/kategori/update', 'KategoriProduk::update', ['filter' => 'role:pusat']);
$routes->delete('/kategori/(:num)', 'KategoriProduk::delete/$1', ['filter' => 'role:pusat']);
$routes->get('/cari-kategori', 'KategoriProduk::searchKategori');

// Supplier Produk
$routes->get('/supplier', 'Supplier::index');
$routes->get('/supplier/new', 'Supplier::add', ['filter' => 'role:pusat']);
$routes->post('/supplier/store', 'Supplier::store', ['filter' => 'role:pusat']);
$routes->get('/supplier/edit/(:segment)', 'Supplier::edit/$1', ['filter' => 'role:pusat']);
$routes->post('/supplier/update', 'Supplier::update', ['filter' => 'role:pusat']);
$routes->delete('/supplier/(:num)', 'Supplier::delete/$1', ['filter' => 'role:pusat']);
$routes->get('/cari-supplier', 'Supplier::searchSupplier');

// Produk
$routes->get('/produk', 'Produk::index');
$routes->get('/produk/new', 'Produk::add', ['filter' => 'role:pusat']);
$routes->post('/produk/store', 'Produk::store', ['filter' => 'role:pusat']);
$routes->get('/produk/edit/(:segment)', 'Produk::edit/$1', ['filter' => 'role:pusat']);
$routes->post('/produk/update', 'Produk::update', ['filter' => 'role:pusat']);
$routes->delete('/produk/(:num)', 'Produk::delete/$1', ['filter' => 'role:pusat']);
$routes->get('/cari-produk', 'Produk::searchProduk');
$routes->post('/produk/excel', 'Produk::produkExcel');

// Kartu Stok
$routes->get('/kartu-stok', 'KartuStok::index');
$routes->get('/kartu-stok/pilih-cabang', 'KartuStok::pilihCabang', ['filter' => 'role:pusat']);
$routes->get('/kartu-stok/stok-awal', 'KartuStok::addStokAwal', ['filter' => 'role:pusat']);
$routes->post('/kartu-stok/store-stok-awal', 'KartuStok::storeStokAwal', ['filter' => 'role:pusat']);
$routes->get('/cari-kartu-stok', 'KartuStok::searchKartuStok');

// Penjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->get('/penjualan/new', 'Penjualan::add', ['filter' => 'role:cabang']);
$routes->post('/penjualan/store', 'Penjualan::store', ['filter' => 'role:cabang']);
$routes->get('/penjualan/cetak-invoice/(:segment)', 'Penjualan::cetakInvoice/$1');
$routes->get('/cari-penjualan', 'Penjualan::searchPenjualan');

// Alokasi
$routes->get('/alokasi', 'Alokasi::index', ['filter' => 'role:pusat']);
$routes->get('/alokasi/new', 'Alokasi::add', ['filter' => 'role:pusat']);
$routes->post('/alokasi/store', 'Alokasi::store', ['filter' => 'role:pusat']);
$routes->get('/alokasi/(:num)', 'Alokasi::detail/$1', ['filter' => 'role:pusat']);
$routes->get('/alokasi-produk/edit/(:num)', 'Alokasi::editProduk/$1', ['filter' => 'role:pusat']);
$routes->post('/alokasi-produk/update', 'Alokasi::updateProduk', ['filter' => 'role:pusat']);
$routes->get('/cari-alokasi', 'Alokasi::searchAlokasi', ['filter' => 'role:pusat']);

// Kelola Profile
$routes->get('/profile', 'UserProfile::index');
$routes->get('/profile/edit', 'UserProfile::edit');
$routes->post('/profile/update', 'UserProfile::update');
$routes->get('/profile/ubah-password', 'UserProfile::ubahPassword');
$routes->post('/profile/simpan-password', 'UserProfile::simpanPassword');
$routes->post('/profile/hapus-foto', 'UserProfile::hapusFoto');
