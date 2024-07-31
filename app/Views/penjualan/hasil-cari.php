<div class="card" id="data-penjualan">
    <div class="card-body">
        <h3 class="card-title">Penjualan - <?= $filter_cabang_nama ?>, <?= $filter_salesman_nama ?>, <?= $filter_produk_nama ?><?= $filter_keyword ? ', Keyword: ' . $filter_keyword : '' ?> (<?= date('F Y', strtotime($filter_waktu)) ?>)</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width: 140px;">Invoice</th>
                    <th style="min-width: 140px;">Tanggal</th>
                    <th style="min-width: 160px;">Cabang</th>
                    <th style="min-width: 140px;">Konsumen</th>
                    <th style="min-width: 160px;">Salesman</th>
                </tr>
                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                <?php if (!empty($data_penjualan)) : ?>
                    <?php foreach ($data_penjualan as $data) : ?>
                        <tr>
                            <td class="text-center"><?= $nomor++ ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('penjualan/cetak-invoice/' . $data['id']) ?>">
                                    <?= $data['no_invoice'] ?>
                                </a>
                            </td>
                            <td class="text-center"><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                            <td class="text-center"><?= $data['nama_cabang'] ?></td>
                            <td class="text-center"><?= $data['nama_konsumen'] ?></td>
                            <td class="text-center"><?= $data['nama_salesman'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="text-center">
                        <td colspan="7">Belum ada data penjualan.</td>
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