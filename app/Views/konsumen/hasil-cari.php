<div class="card" id="data-konsumen">
    <div class="card-body">
        <h3 class="card-title">Data Konsumen - <?= $filter_cabang ?><?= $filter_keyword ? ', Keyword: ' . $filter_keyword : '' ?></h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width: 140px;">Nama</th>
                    <th style="min-width: 140px;">Alamat</th>
                    <th style="min-width: 140px;">Kota</th>
                    <th style="min-width: 140px;">Cabang</th>
                    <th style="min-width: 140px;">No. Telp</th>
                    <th style="min-width: 160px;">Aksi</th>
                </tr>
                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                <?php if (!empty($data_konsumen)) : ?>
                    <?php foreach ($data_konsumen as $data) : ?>
                        <tr>
                            <td class="text-center"><?= $nomor++ ?></td>
                            <td class="text-center"><?= $data['nama'] ?></td>
                            <td class="text-center"><?= $data['alamat'] ?></td>
                            <td class="text-center"><?= $data['kota'] ?></td>
                            <td class="text-center"><?= $data['nama_cabang'] ?></td>
                            <td class="text-center"><?= $data['no_telp'] ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('konsumen/edit/' . $data['slug']) ?>" class="badge bg-warning">Edit</a>
                                <?php if (in_groups('pusat')) : ?>
                                    <a href="/delete" class="badge bg-danger btn-hapus" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="<?= $data['id'] ?>" data-name="<?= $data['nama'] ?>">
                                        Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="text-center">
                        <td colspan="7">Belum ada data konsumen.</td>
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