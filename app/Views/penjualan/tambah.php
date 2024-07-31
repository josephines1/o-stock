<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/penjualan/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Konsumen</label>
                                <select name="konsumen" id="konsumen" type="text" class="form-select <?= validation_show_error('konsumen') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Konsumen---</option>
                                    <?php if (!empty($data_konsumen)) : ?>
                                        <?php foreach ($data_konsumen as $konsumen) : ?>
                                            <option value="<?= $konsumen['id'] ?>" <?= old('konsumen') === $konsumen['id'] ? 'selected' : '' ?>><?= $konsumen['no_telp'] ?> - [<?= $konsumen['nama'] ?>]</option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan konsumen</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('konsumen')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('konsumen') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Salesman</label>
                                <select name="salesman" id="salesman" type="text" class="form-select <?= validation_show_error('salesman') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Salesman---</option>
                                    <?php if (!empty($data_salesman)) : ?>
                                        <?php foreach ($data_salesman as $salesman) : ?>
                                            <option value="<?= $salesman['id'] ?>" <?= old('salesman') === $salesman['id'] ? 'selected' : '' ?>><?= $salesman['nama'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan salesman</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('salesman')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('salesman') ?>
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
                                $oldProduk = old('produk') ?? [''];
                                $oldJumlah = old('jumlah') ?? [''];
                                $oldDisc = old('disc') ?? ['0'];

                                $groupCount = max(count($oldProduk), count($oldJumlah), count($oldDisc));

                                for ($i = 0; $i < $groupCount; $i++) :
                                ?>
                                    <div class="row align-items-stretch mb-3">
                                        <div class="col-4">
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
                                        <div class="col-3">
                                            <label class="form-label">Kuantitas</label>
                                            <input type="text" name="jumlah[]" value="<?= esc($oldJumlah[$i] ?? '') ?>" class="form-control <?= validation_show_error('jumlah.' . $i) ? 'is-invalid' : '' ?>" placeholder="e.g. 10">
                                            <?php if (validation_show_error('jumlah.' . $i)) : ?>
                                                <div class="invalid-feedback" id="jumlah">
                                                    <?= validation_show_error('jumlah.' . $i) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-3">
                                            <label class="form-label">Disc (%)</label>
                                            <input type="text" name="disc[]" value="<?= esc($oldDisc[$i] ?? 0) ?>" class="form-control <?= validation_show_error('disc.' . $i) ? 'is-invalid' : '' ?>" placeholder="e.g. 5">
                                            <?php if (validation_show_error('disc.' . $i)) : ?>
                                                <div class="invalid-feedback">
                                                    <?= validation_show_error('disc.' . $i) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-auto">
                                            <?php if ($i === 0) : ?>
                                                <button type="button" style="height: 100%;" id="add_input_field_btn" class="btn btn-ghost-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus m-auto">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                                        <path d="M9 12h6" />
                                                        <path d="M12 9v6" />
                                                    </svg>
                                                </button>
                                            <?php else : ?>
                                                <button type="button" style="height: 100%;" class="btn btn-ghost-danger remove_input_field_btn">
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
                                <a href="<?= base_url('penjualan') ?>" class="btn btn-link">Batal</a>
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
                    <div class="col-4">
                        <label class="form-label">Produk</label>
                        <select name="produk[]" type="text" class="form-select produk-select">
                            <option value="">Pilih Produk</option>
                            <?php foreach ($data_produk as $produk) : ?>
                                <option value="<?= $produk['id'] ?>" <?= esc($oldProduk[$i] ?? '') == $produk['id'] ? 'selected' : '' ?>><?= $produk['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <label class="form-label">Kuantitas</label>
                        <input type="text" name="jumlah[]" class="form-control" placeholder="e.g. 10">
                    </div>
                    <div class="col-3">
                        <label class="form-label">Disc (%)</label>
                        <input type="text" name="disc[]" value="0" class="form-control" placeholder="e.g. 5">
                    </div>
                    <div class="col-auto">
                        <button type="button" style="height: 100%;" class="btn btn-ghost-danger remove_input_field_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x m-auto">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M10 10l4 4m0 -4l-4 4" />
                            </svg>
                        </button>
                    </div>
                </div>
                `
            );

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

        $('#konsumen').select2({
            placeholder: "---Pilih Konsumen---",
            allowClear: false,
            width: '100%',
        });

        $('#salesman').select2({
            placeholder: "---Pilih salesman---",
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
    })
</script>
<?= $this->endSection() ?>