<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/profile/simpan-password') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <?php if (session()->getFlashdata('password-salah')) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= session()->getFlashdata('password-salah') ?>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3 w-100">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_pass" class="form-control <?= validation_show_error('current_pass') ? 'is-invalid' : '' ?>" placeholder="Password saat ini">
                                <?php if (validation_show_error('current_pass')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('current_pass') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_pass" class="form-control <?= validation_show_error('new_pass') ? 'is-invalid' : '' ?>" placeholder="Password Baru">
                                <?php if (validation_show_error('new_pass')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('new_pass') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="conf_pass" class="form-control <?= validation_show_error('conf_pass') ? 'is-invalid' : '' ?>" placeholder="Konfirmasi Password Baru">
                                <?php if (validation_show_error('conf_pass')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('conf_pass') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('profile') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>