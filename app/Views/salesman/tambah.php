<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/salesman/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama</label>
                                <input name="nama" type="text" class="form-control <?= validation_show_error('nama') ? 'is-invalid' : '' ?>" placeholder="Nama Salesman" value="<?= old('nama') ?>" autofocus>
                                <?php if (validation_show_error('nama')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('nama') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Nomor Handphone</label>
                                <input name="no_handphone" type="text" class="form-control <?= validation_show_error('no_handphone') ? 'is-invalid' : '' ?>" placeholder="No. HP Salesman" value="<?= old('no_handphone') ?>">
                                <?php if (validation_show_error('no_handphone')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('no_handphone') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (in_groups('pusat')) : ?>
                                <div class="mb-3 w-100">
                                    <label class="form-label">Cabang</label>
                                    <select name="cabang" id="cabang" type="text" class="form-select <?= validation_show_error('cabang') ? 'is-invalid' : '' ?>">
                                        <option value="">---Pilih Cabang---</option>
                                        <?php if (!empty($cabang_options)) : ?>
                                            <?php foreach ($cabang_options as $option) : ?>
                                                <option value="<?= $option['id'] ?>" <?= old('cabang') === $option['id'] ? 'selected' : '' ?>><?= $option['nama'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="">Tidak ada pilihan cabang</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (validation_show_error('cabang')) : ?>
                                        <div class="invalid-feedback">
                                            <?= validation_show_error('cabang') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else : ?>
                                <input type="hidden" name="cabang" value="<?= $user_kantor ?>">
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('salesman') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#cabang').select2({
            placeholder: "---Pilih Cabang---",
            allowClear: false,
            width: '100%'
        });
    });
</script>
<?= $this->endSection() ?>