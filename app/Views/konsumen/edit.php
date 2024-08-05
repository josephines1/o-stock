<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/konsumen/update') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="slug_db" value="<?= $db['slug'] ?>">
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Konsumen</label>
                                <input name="nama" type="text" class="form-control <?= validation_show_error('nama') ? 'is-invalid' : '' ?>" placeholder="Nama Konsumen" value="<?= old('nama', htmlspecialchars($db['nama'])) ?>">
                                <?php if (validation_show_error('nama')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('nama') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Alamat</label>
                                <input name="alamat" type="text" class="form-control <?= validation_show_error('alamat') ? 'is-invalid' : '' ?>" placeholder="Alamat Konsumen" value="<?= old('alamat', htmlspecialchars($db['alamat'])) ?>">
                                <?php if (validation_show_error('alamat')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('alamat') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Kota</label>
                                <input name="kota" type="text" class="form-control <?= validation_show_error('kota') ? 'is-invalid' : '' ?>" placeholder="Kota Konsumen" value="<?= old('kota', htmlspecialchars($db['kota'])) ?>">
                                <?php if (validation_show_error('kota')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('kota') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Nomor Handphone</label>
                                <input name="no_handphone" type="text" class="form-control <?= validation_show_error('no_handphone') ? 'is-invalid' : '' ?>" placeholder="No. HP Konsumen" value="<?= old('no_handphone', $db['no_telp']) ?>">
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
                                                <option value="<?= $option['id'] ?>" <?= old('cabang', $db['id_cabang']) === $option['id'] ? 'selected' : '' ?>><?= $option['nama'] ?></option>
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
                                <a href="<?= base_url('konsumen') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Edit</button>
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