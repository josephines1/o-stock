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