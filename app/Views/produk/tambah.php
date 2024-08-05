<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/produk/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Kode Produk</label>
                                <input name="kode_produk" type="text" class="form-control <?= validation_show_error('kode_produk') ? 'is-invalid' : '' ?>" placeholder="Kode Produk" value="<?= old('kode_produk') ?>" autofocus>
                                <?php if (validation_show_error('kode_produk')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('kode_produk') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Produk</label>
                                <input name="nama" type="text" class="form-control <?= validation_show_error('nama') ? 'is-invalid' : '' ?>" placeholder="Nama Produk" value="<?= old('nama') ?>">
                                <?php if (validation_show_error('nama')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('nama') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Supplier</label>
                                <select name="supplier" id="supplier" type="text" class="form-select <?= validation_show_error('supplier') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Supplier---</option>
                                    <?php if (!empty($data_supplier)) : ?>
                                        <?php foreach ($data_supplier as $supplier) : ?>
                                            <option value="<?= $supplier['id'] ?>" <?= old('supplier') === $supplier['id'] ? 'selected' : '' ?>><?= $supplier['nama'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan supplier</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('supplier')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('supplier') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Harga Jual</label>
                                <input name="harga_jual" type="text" class="form-control <?= validation_show_error('harga_jual') ? 'is-invalid' : '' ?>" placeholder="Harga Jual" value="<?= old('harga_jual') ?>">
                                <?php if (validation_show_error('harga_jual')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('harga_jual') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" id="kategori" type="text" class="form-select <?= validation_show_error('kategori') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Kategori---</option>
                                    <?php if (!empty($data_kategori)) : ?>
                                        <?php foreach ($data_kategori as $kategori) : ?>
                                            <option value="<?= $kategori['id'] ?>" <?= old('kategori') === $kategori['id'] ? 'selected' : '' ?>><?= $kategori['nama'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan kategori</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('kategori')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('kategori') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('produk') ?>" class="btn btn-link">Batal</a>
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
        $('#supplier').select2({
            placeholder: "---Pilih Supplier---",
            allowClear: false,
            width: '100%'
        });
        $('#kategori').select2({
            placeholder: "---Pilih Kategori---",
            allowClear: false,
            width: '100%'
        });
    });
</script>
<?= $this->endSection() ?>