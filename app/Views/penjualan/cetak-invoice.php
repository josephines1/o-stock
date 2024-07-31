<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<style>
    .card {
        font-size: 80%;
    }

    .markdown>table>:not(caption)>*>*,
    .table>:not(caption)>*>* {
        border-bottom-width: 0;
        padding: 7px;
    }

    .table-bordered {
        border: 1pt;
    }

    .text-right {
        text-align: end;
    }

    .page-break {
        page-break-after: always;
    }

    .cetak-time {
        position: absolute;
        bottom: 0;
        right: 0;
    }
</style>
<style media="print">
    @page {
        size: 210mm 148.5mm portrait;
        /* Lebar A4 (210mm) dan setengah dari tinggi A4 (210mm / 2 = 148.5mm) */
        /* Ofuku Hagaki */
        padding: 0;
        margin: 0;
    }
</style>
<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center mb-4">
            <div class="col-md-auto m-0 d-print-none">
                <h2 class="page-title">
                    <?= $title; ?>
                </h2>
            </div>

            <!-- Page title actions -->
            <div class="col-md-auto ms-auto mt-4 mt-md-0 d-print-none">
                <div class="d-flex flex-wrap flex-md-nowrap">
                    <button type="button" class="btn btn-primary ms-auto ms-md-3 mt-3 mt-md-0" id="print-button" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                        </svg>
                        <span>Cetak</span>
                    </button>
                </div>
            </div>
        </div>

        <?php $total_bayar = 0; ?>
        <?php foreach ($produks as $produk) : ?>
            <?php if ($produk['page'] != 1) : ?>
                <div class="page-break"></div> <!-- Add this line to create a page break -->
            <?php endif; ?>
            <div class="row my-3">
                <div class="col-lg-12">
                    <div class="card mx-auto" style="width: 9.5in; height: 6.5in;">
                        <div class="card-body">
                            <h2 class="text-center pt-4">INVOICE</h2>
                            <div class="row align-items-center">
                                <div class="col-7">
                                    <div class="table-responsive">
                                        <table class="table border-none">
                                            <tr>
                                                <th class="pt-0">Tanggal</th>
                                                <td class="pt-0"><?= date('d/m/Y', strtotime($db['tanggal'])) ?></td>
                                            </tr>
                                            <tr>
                                                <th class="pt-0">No. Invoice</th>
                                                <td class="pt-0"><?= $db['no_invoice'] ?></td>
                                            </tr>
                                            <tr>
                                                <th class="pt-0">Nama</th>
                                                <td class="pt-0"><?= $db['nama_konsumen'] ?></td>
                                            </tr>
                                            <tr>
                                                <th class="pt-0">Alamat</th>
                                                <td class="pt-0"><?= $db['alamat_konsumen'] ?>, <?= $db['kota_konsumen'] ?></td>
                                            </tr>
                                            <tr>
                                                <th class="pt-0">No. Telp</th>
                                                <td class="pt-0"><?= $db['no_telp_konsumen'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="table-responsive">
                                        <table class="table border-none">
                                            <tr class="m-0">
                                                <th class="pt-0">
                                                    <h1 class="m-0"><?= $db['nama_cabang']; ?></h1>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td class="pt-0"><?= $db['alamat_cabang'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr class="text-center">
                                            <th>No.</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Satuan</th>
                                            <th>Discount (%)</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                        <?php $nomor = 1 + ($perPage * ($produk['page'] - 1)); ?>
                                        <?php if (!empty($produk['data'])) : ?>
                                            <?php foreach ($produk['data'] as $p) : ?>
                                                <?php
                                                // Hitung harga setelah diskon
                                                $harga_setelah_diskon = $p['harga'] - ($p['harga'] * ($p['discount'] / 100));

                                                // Hitung total harga
                                                $total_harga = $harga_setelah_diskon * $p['jumlah'];

                                                // Format total harga dengan menggunakan number_format
                                                $p['total_harga_formatted'] = number_format($total_harga, 0, ',', '.');

                                                $total_bayar += $total_harga;
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?= $nomor++ ?></td>
                                                    <td class="text-center"><?= $p['kode_produk'] ?></td>
                                                    <td class="text-center"><?= $p['nama_produk'] ?></td>
                                                    <td class="text-center">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?= $p['discount'] ?></td>
                                                    <td class="text-center"><?= $p['jumlah'] ?></td>
                                                    <td class="text-right">Rp <?= $p['total_harga_formatted'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr class="text-center">
                                                <td colspan="7">Belum ada data penjualan.</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if ($produk['page'] == $total_pages) : ?>
                                            <tr class="text-center">
                                                <th colspan="6">Total Bayar</th>
                                                <th class="text-right">Rp <?= number_format($total_bayar, 0, ',', '.') ?></th>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <p><strong>Salesman:</strong></span> <?= $db['nama_salesman'] ?></p>
                                </div>
                            </div>
                            <div class="row mt-3 justify-content-between">
                                <div class="col-6 text-center my-5">
                                    <p>Konsumen</p>
                                    <p class="mt-5">_________________</p>
                                </div>
                                <div class="col-6 text-center my-5">
                                    <p>Cashier</p>
                                    <p class="mt-5">_________________</p>
                                </div>
                            </div>
                        </div>
                        <p class="me-4 italic cetak-time"><i><small>Dicetak pada <?= date('d F Y H:i:s') ?></small></i></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function(e) {
        $('#print-button').click(function() {
            window.print();
        });
    })
</script>
<?= $this->endSection(); ?>