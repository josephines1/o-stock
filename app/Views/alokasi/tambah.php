<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/alokasi/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Cabang</label>
                                <select id="cabang" name="cabang" id="cabang" type="text" class="form-select <?= validation_show_error('cabang') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Cabang---</option>
                                    <?php if (!empty($data_cabang)) : ?>
                                        <?php foreach ($data_cabang as $cabang) : ?>
                                            <option value="<?= $cabang['id'] ?>" <?= old('cabang') === $cabang['id'] ? 'selected' : '' ?>><?= $cabang['nama'] ?></option>
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
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="show_item">
                                <?php
                                // Ambil old values untuk produk dan jumlah
                                $oldProduk = old('produk') ?? [''];
                                $oldJumlah = old('jumlah') ?? [''];

                                // Iterasi sesuai dengan jumlah group field yang ingin ditampilkan
                                $groupCount = max(count($oldProduk), count($oldJumlah));

                                for ($i = 0; $i < $groupCount; $i++) :
                                ?>
                                    <div class="row align-items-stretch mb-3">
                                        <div class="col-5">
                                            <label class="form-label">Produk</label>
                                            <select name="produk[]" type="text" class="produk-select form-select <?= validation_show_error('produk.' . $i) ? 'is-invalid' : '' ?>">
                                                <?php if ($data_produk) : ?>
                                                    <option value="">Pilih Produk</option>
                                                    <?php foreach ($data_produk as $produk) : ?>
                                                        <option value="<?= $produk['id'] ?>" <?= esc($oldProduk[$i] ?? '') == $produk['id'] ? 'selected' : '' ?>><?= $produk['nama'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <option value="">Belum ada data produk.</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if (validation_show_error('produk.' . $i)) : ?>
                                                <div class="invalid-feedback">
                                                    <?= validation_show_error('produk.' . $i) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label">Kuantitas</label>
                                            <input type="text" name="jumlah[]" value="<?= esc($oldJumlah[$i] ?? '') ?>" class="form-control <?= validation_show_error('jumlah.' . $i) ? 'is-invalid' : '' ?>" placeholder="Kuantitas">
                                            <?php if (validation_show_error('jumlah.' . $i)) : ?>
                                                <div class="invalid-feedback">
                                                    <?= validation_show_error('jumlah.' . $i) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-auto">
                                            <?php if ($i === 0) : ?>
                                                <button type="button" style="height:100%;" id="add_input_field_btn" class="btn btn-ghost-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus m-auto">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                        <path d="M9 12h6" />
                                                        <path d="M12 9v6" />
                                                    </svg>
                                                </button>
                                            <?php else : ?>
                                                <button type="button" style="height:100%;" class="btn btn-ghost-danger remove_input_field_btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x m-auto">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                        <path d="M10 10l4 4m0 -4l-4 4" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('alokasi') ?>" class="btn btn-link">Batal</a>
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
        $("#add_input_field_btn").click(function(e) {
            e.preventDefault();
            $("#show_item").append(
                `                 
                <div class="row align-items-stretch mb-3">
                    <div class="col-5">
                        <label class="form-label">Produk</label>
                        <select name="produk[]" type="text" class="form-select produk-select">
                            <option value="">Pilih Produk</option>
                            <?php foreach ($data_produk as $produk) : ?>
                                <option value="<?= $produk['id'] ?>" <?= esc($oldProduk[$i] ?? '') == $produk['id'] ? 'selected' : '' ?>><?= $produk['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-5">
                        <label class="form-label">Kuantitas</label>
                        <input type="text" name="jumlah[]" class="form-control" placeholder="Kuantitas">
                    </div>
                    <div class="col-auto">
                        <button type="button" style="height:100%;" class="btn btn-ghost-danger remove_input_field_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x m-auto">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M10 10l4 4m0 -4l-4 4" />
                            </svg>
                        </button>
                    </div>
                </div>
                `
            )

            $('.produk-select').select2({
                placeholder: "---Pilih Produk---",
                allowClear: false,
                width: '100%',
            });
        });

        $(document).on('click', '.remove_input_field_btn', function(e) {
            e.preventDefault();
            $(this).closest('.row.align-items-stretch.mb-3').remove();
        });

        $('#cabang').select2({
            placeholder: "---Pilih Cabang---",
            allowClear: false,
            width: '100%',
        });

        $('.produk-select').each(function() {
            $(this).select2({
                placeholder: "---Pilih Produk---",
                allowClear: false,
                width: '100%',
            });
        });
    });
</script>
<?= $this->endSection() ?>