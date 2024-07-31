<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center mb-4">
            <div class="col-md-auto m-0 d-print-none">
                <h2 class="page-title">
                    <?= $title; ?>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="data-alokasi">
                    <div class="card-body">
                        <h3 class="card-title">Produk Alokasi - <?= $no_alokasi ?></h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th style="min-width: 140px;">Kode Produk</th>
                                    <th style="min-width: 140px;">Nama Produk</th>
                                    <th style="min-width: 140px;">Jumlah</th>
                                    <th style="min-width: 50px;">Aksi</th>
                                </tr>
                                <?php $nomor = 1; ?>
                                <?php if (!empty($alokasi_produks)) : ?>
                                    <?php foreach ($alokasi_produks as $data) : ?>
                                        <tr>
                                            <td class="text-center"><?= $nomor++ ?></td>
                                            <td class="text-center"><?= $data['kode_produk'] ?></td>
                                            <td class="text-center"><?= $data['nama_produk'] ?></td>
                                            <td class="text-center"><?= $data['jumlah'] ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url('/alokasi-produk/edit/' . $data['id']) ?>" class="badge bg-warning">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="text-center">
                                        <td colspan="4">Produk alokasi tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?= base_url('alokasi') ?>" class="btn btn-ghost-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>