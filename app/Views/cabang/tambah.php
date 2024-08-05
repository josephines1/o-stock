<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/cabang/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Cabang</label>
                                <input name="nama" type="text" class="form-control <?= validation_show_error('nama') ? 'is-invalid' : '' ?>" placeholder="Nama Cabang" value="<?= old('nama') ?>" autofocus>
                                <?php if (validation_show_error('nama')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('nama') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Kode Cabang</label>
                                <input name="kode" type="text" class="form-control <?= validation_show_error('kode') ? 'is-invalid' : '' ?>" placeholder="Kode Cabang" value="<?= old('kode') ?>">
                                <?php if (validation_show_error('kode')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('kode') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Alamat</label>
                                <input name="alamat" type="text" class="form-control <?= validation_show_error('alamat') ? 'is-invalid' : '' ?>" placeholder="Alamat Cabang" value="<?= old('alamat') ?>">
                                <?php if (validation_show_error('alamat')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('alamat') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('cabang') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>