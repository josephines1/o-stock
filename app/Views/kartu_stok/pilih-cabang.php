<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/kartu-stok/stok-awal') ?>" method="get">
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Pilih Cabang</label>
                                <select name="cabang" id="cabang" type="text" class="form-select <?= validation_show_error('cabang') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Cabang---</option>
                                    <?php if (!empty($data_cabang)) : ?>
                                        <?php foreach ($data_cabang as $option) : ?>
                                            <option value="<?= $option['slug'] ?>" <?= old('cabang') === $option['slug'] ? 'selected' : '' ?>><?= $option['nama'] ?></option>
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
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('kartu-stok') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Next</button>
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