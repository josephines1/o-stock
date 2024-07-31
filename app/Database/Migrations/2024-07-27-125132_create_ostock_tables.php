<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOStockTables extends Migration
{
    public function up()
    {
        // Tabel Kantor
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_cabang'      => ['type' => 'varchar', 'constraint' => 10],
            'tipe'             => ['type' => 'varchar', 'constraint' => 50],
            'nama'             => ['type' => 'varchar', 'constraint' => 50],
            'slug'             => ['type' => 'varchar', 'constraint' => 100],
            'alamat'           => ['type' => 'varchar', 'constraint' => 255],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kantor', true);

        // Tabel Salesman
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_cabang'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nama'              => ['type' => 'varchar', 'constraint' => 255],
            'slug'              => ['type' => 'varchar', 'constraint' => 255],
            'no_telp'           => ['type' => 'varchar', 'constraint' => 50],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_cabang', 'kantor', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('salesman', true);

        // Tabel Konsumen
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_cabang'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'nama'              => ['type' => 'varchar', 'constraint' => 255],
            'slug'              => ['type' => 'varchar', 'constraint' => 255],
            'alamat'            => ['type' => 'varchar', 'constraint' => 255],
            'kota'              => ['type' => 'varchar', 'constraint' => 100],
            'no_telp'           => ['type' => 'varchar', 'constraint' => 50],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_cabang', 'kantor', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('konsumen', true);

        // Tabel Kategori Produk
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'              => ['type' => 'varchar', 'constraint' => 50],
            'slug'              => ['type' => 'varchar', 'constraint' => 100],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kategori_produk', true);

        // Tabel Supplier
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama'              => ['type' => 'varchar', 'constraint' => 255],
            'slug'              => ['type' => 'varchar', 'constraint' => 255],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('supplier', true);

        // Tabel Produk
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_kategori'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_supplier'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'kode_produk'       => ['type' => 'varchar', 'constraint' => 100],
            'nama'              => ['type' => 'varchar', 'constraint' => 255],
            'slug'              => ['type' => 'varchar', 'constraint' => 255],
            'harga_jual'        => ['type' => 'int', 'constraint' => 11],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_kategori', 'kategori_produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('produk', true);

        // Tabel Penjualan
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_cabang'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_konsumen'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_salesman'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'no_invoice'        => ['type' => 'varchar', 'constraint' => 50],
            'tanggal'           => ['type' => 'date', 'null' => false],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_cabang', 'kantor', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_konsumen', 'konsumen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_salesman', 'salesman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penjualan', true);

        // Tabel Penjualan Per Produk
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_penjualan'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_produk'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'harga'             => ['type' => 'int', 'constraint' => 11],
            'jumlah'            => ['type' => 'int', 'constraint' => 11],
            'discount'          => ['type' => 'float'],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produk', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penjualan_per_produk', true);

        // Tabel Kartu Stok
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_produk'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_cabang'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'tanggal'           => ['type' => 'date', 'null' => false],
            'no_bukti'          => ['type' => 'varchar', 'constraint' => 50],
            'keterangan'        => ['type' => 'varchar', 'constraint' => 255],
            'stok_masuk'        => ['type' => 'int', 'constraint' => 11],
            'stok_keluar'       => ['type' => 'int', 'constraint' => 11],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_produk', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_cabang', 'kantor', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kartu_stok', true);

        // Tabel Alokasi
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_cabang'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'no_alokasi'        => ['type' => 'varchar', 'constraint' => 50],
            'tanggal'           => ['type' => 'date', 'null' => false],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_cabang', 'kantor', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('alokasi', true);

        // Tabel Alokasi Per Produk
        $this->forge->addField([
            'id'                => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_alokasi'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'id_produk'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'jumlah'            => ['type' => 'int', 'constraint' => 11],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_alokasi', 'alokasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produk', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('alokasi_per_produk', true);
    }

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver !== 'SQLite3') { // @phpstan-ignore-line
            $this->forge->dropForeignKey('salesman', 'salesman_id_cabang_foreign');
            $this->forge->dropForeignKey('konsumen', 'konsumen_id_cabang_foreign');
            $this->forge->dropForeignKey('produk', 'produk_id_kategori_foreign');
            $this->forge->dropForeignKey('produk', 'produk_id_supplier_foreign');
            $this->forge->dropForeignKey('penjualan', 'penjualan_id_cabang_foreign');
            $this->forge->dropForeignKey('penjualan', 'penjualan_id_konsumen_foreign');
            $this->forge->dropForeignKey('penjualan', 'penjualan_id_salesman_foreign');
            $this->forge->dropForeignKey('penjualan_per_produk', 'penjualan_per_produk_id_penjualan_foreign');
            $this->forge->dropForeignKey('penjualan_per_produk', 'penjualan_per_produk_id_produk_foreign');
            $this->forge->dropForeignKey('kartu_stok', 'kartu_stok_id_produk_foreign');
            $this->forge->dropForeignKey('kartu_stok', 'kartu_stok_id_cabang_foreign');
            $this->forge->dropForeignKey('alokasi', 'alokasi_id_cabang_foreign');
            $this->forge->dropForeignKey('alokasi_per_produk', 'alokasi_per_produk_id_alokasi_foreign');
            $this->forge->dropForeignKey('alokasi_per_produk', 'alokasi_per_produk_id_produk_foreign');
        }

        $this->forge->dropTable('kantor', true);
        $this->forge->dropTable('salesman', true);
        $this->forge->dropTable('konsumen', true);
        $this->forge->dropTable('kategori_produk', true);
        $this->forge->dropTable('supplier', true);
        $this->forge->dropTable('produk', true);
        $this->forge->dropTable('penjualan', true);
        $this->forge->dropTable('penjualan_per_produk', true);
        $this->forge->dropTable('kartu_stok', true);
        $this->forge->dropTable('alokasi', true);
        $this->forge->dropTable('alokasi_per_produk', true);
    }
}
