<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/alokasi-produk/update') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="id_alokasiPerProduk" value="<?= $db['id'] ?>">
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" disabled class="form-control <?= validation_show_error('nama') ? 'is-invalid' : '' ?>" value="<?= old('nama', htmlspecialchars($db['nama_produk'])) ?>">
                                <?php if (validation_show_error('nama')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('nama') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Jumlah</label>
                                <input type="text" name="jumlah" class="form-control <?= validation_show_error('jumlah') ? 'is-invalid' : '' ?>" placeholder="e.g. Jumlah Produk Alokasi" value="<?= old('jumlah', $db['jumlah']) ?>">
                                <?php if (validation_show_error('jumlah')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('jumlah') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('alokasi/' . $db['id_alokasi']) ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Edit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>