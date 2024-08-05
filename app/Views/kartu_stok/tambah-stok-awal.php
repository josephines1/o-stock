<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/kartu-stok/store-stok-awal') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <?php if (in_groups('pusat')) : ?>
                                <input type="hidden" name="cabang" value="<?= $slug_cabang ?>">
                            <?php endif; ?>
                            <div class="mb-3 w-100">
                                <label class="form-label">Cabang</label>
                                <input type="text" class="form-control" disabled value="<?= $nama_cabang ?>">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Produk</label>
                                <select name="produk" id="produk" type="text" class="form-select <?= validation_show_error('produk') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Produk---</option>
                                    <?php if (!empty($data_produk)) : ?>
                                        <?php foreach ($data_produk as $option) : ?>
                                            <option value="<?= $option['id'] ?>" <?= old('produk') === $option['id'] ? 'selected' : '' ?>><?= $option['nama_produk'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan produk</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('produk')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('produk') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Stok Awal</label>
                                <input name="stok_awal" type="text" class="form-control <?= validation_show_error('stok_awal') ? 'is-invalid' : '' ?>" placeholder="Jumlah Stok Awal" value="<?= old('stok_awal') ?>">
                                <?php if (validation_show_error('stok_awal')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('stok_awal') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <?php if (in_groups('pusat')) : ?>
                                    <a href="<?= base_url('kartu-stok/pilih-cabang') ?>" class="btn btn-link">Kembali</a>
                                <?php else : ?>
                                    <a href="<?= base_url('kartu-stok') ?>" class="btn btn-link">Batal</a>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary ms-auto">Simpan Stok Awal</button>
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
        $('#produk').select2({
            placeholder: "---Pilih Produk---",
            allowClear: false,
            width: '100%'
        });
    });
</script>
<?= $this->endSection() ?>