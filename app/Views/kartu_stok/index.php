<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<div class="page-header">
    <div class="container-xl">
        <div class="row g-2 align-items-center mb-4">
            <div class="col-md-auto m-0 d-print-none">
                <h2 class="page-title">
                    <?= $title; ?>
                </h2>
            </div>

            <!-- Page title actions -->
            <div class="col-md-auto ms-auto mt-4 mt-md-0 d-print-none">
                <div class="d-flex flex-wrap flex-md-nowrap">
                    <div class="dropdown ms-md-3">
                        <a href="#filter" class="btn btn-secondary-outline text-secondary dropdown-toggle" data-bs-toggle="dropdown">Filter Data</a>
                        <div class="dropdown-menu dropdown-menu-arrow" style="max-width: 350px;min-width: 200px;">
                            <h3 class="dropdown-header">Filters</h3>
                            <?php if (in_groups('pusat')) : ?>
                                <div class="w-100 p-2">
                                    <select class="form-select" id="cabang" name="cabang">
                                        <option value="0" <?= ($filter_cab == '0') ? 'selected' : '' ?>>Semua Cabang</option>
                                        <?php foreach ($data_cabang as $cabang) : ?>
                                            <option value=<?= $cabang['id'] ?> <?= ($filter_cab == $cabang['id']) ? 'selected' : '' ?>><?= $cabang['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="w-100 p-2">
                                <select class="form-select" id="supplier" name="supplier">
                                    <option value="0" <?= ($filter_sup == '0') ? 'selected' : '' ?>>Semua Supplier</option>
                                    <?php foreach ($data_supplier as $supplier) : ?>
                                        <option value=<?= $supplier['id'] ?> <?= ($filter_sup == $supplier['id']) ? 'selected' : '' ?>><?= $supplier['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="w-100 p-2">
                                <select class="form-select" id="produk" name="produk">
                                    <option value="0" <?= ($filter_pro == '0') ? 'selected' : '' ?>>Semua Produk</option>
                                    <?php foreach ($data_produk as $produk) : ?>
                                        <option value=<?= $produk['id'] ?> <?= ($filter_pro == $produk['id']) ? 'selected' : '' ?>><?= $produk['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        </form>
                    </div>
                    <?php if (in_groups('pusat')) : ?>
                        <a href="<?= base_url("/kartu-stok/pilih-cabang") ?>" class="btn btn-primary ms-auto ms-md-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Tambah Stok Awal
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="data-kartu-stok">
                    <div class="card-body">
                        <h3 class="card-title">Kartu Stok - <?= $filter_cabang ?>, <?= $filter_supplier ?>, <?= $filter_produk ?></h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th style="min-width: 100px;">Nama Cabang</th>
                                    <th style="min-width: 100px;">Kode Produk</th>
                                    <th style="min-width: 100px;">Nama Produk</th>
                                    <th style="min-width: 100px;">Supplier</th>
                                    <th style="min-width: 80px;">Stok Akhir</th>
                                </tr>
                                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                                <?php if (!empty($data_kartu_stok)) : ?>
                                    <?php foreach ($data_kartu_stok as $data) : ?>
                                        <tr>
                                            <td class="text-center"><?= $nomor++ ?></td>
                                            <td class="text-center"><?= $data['nama_cabang'] ?></td>
                                            <td class="text-center"><?= $data['kode_produk'] ?></td>
                                            <td class="text-center"><?= $data['nama_produk'] ?></td>
                                            <td class="text-center"><?= $data['nama_supplier'] ?></td>
                                            <td class="text-center"><?= $data['stok_akhir'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="text-center">
                                        <td colspan="6">Belum ada data stok.</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <p class="m-0 text-muted">Showing <span><?= ($perPage * ($currentPage - 1)) + 1 ?></span> to <span><?= min($perPage * $currentPage, $total) ?></span> of <span><?= $total ?></span> entries</p>
                        <?= $pager; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#cabang').change(function() {
            $.get('cari-kartu-stok?cabang=' + $('#cabang').val() + '&produk=' + $('#produk').val() + '&supplier=' + $('#supplier').val(), function(data) {
                $('#data-kartu-stok').html(data);
            })
        })

        $('#supplier').change(function() {
            $.get('cari-kartu-stok?cabang=' + $('#cabang').val() + '&produk=' + $('#produk').val() + '&supplier=' + $('#supplier').val(), function(data) {
                $('#data-kartu-stok').html(data);
            })
        })

        $('#produk').change(function() {
            $.get('cari-kartu-stok?cabang=' + $('#cabang').val() + '&produk=' + $('#produk').val() + '&supplier=' + $('#supplier').val(), function(data) {
                $('#data-kartu-stok').html(data);
            })
        })
    })
</script>
<?= $this->endSection() ?>