<div class="card" id="data-alokasi">
    <div class="card-body">
        <h3 class="card-title">Alokasi - <?= $filter_cabang_nama ?> - <?= $filter_produk_nama ?><?= $filter_key ? ', Keyword: ' . $filter_key : '' ?> (<?= date('F Y', strtotime($filter_waktu)) ?>)</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width: 140px;">No. Alokasi</th>
                    <th style="min-width: 140px;">Tanggal</th>
                    <th style="min-width: 140px;">Cabang</th>
                </tr>
                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                <?php if (!empty($data_alokasi)) : ?>
                    <?php foreach ($data_alokasi as $data) : ?>
                        <tr>
                            <td class="text-center"><?= $nomor++ ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('/alokasi/' . $data['id']); ?>"><?= $data['no_alokasi'] ?></a>

                            </td>
                            <td class="text-center"><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                            <td class="text-center"><?= $data['nama_cabang'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="text-center">
                        <td colspan="4">Belum ada data alokasi.</td>
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