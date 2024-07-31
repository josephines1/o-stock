<div class="card" id="data-produk">
    <div class="card-body">
        <h3 class="card-title">Produk - <?= $filter_kategori ?>, <?= $filter_supplier ?></h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width: 50px;">Kode Produk</th>
                    <th style="min-width: 160px;">Nama Produk</th>
                    <th style="min-width: 120px;">Harga Jual</th>
                    <th style="min-width: 100px;">Kategori</th>
                    <th style="min-width: 100px;">Supplier</th>
                    <?php if (in_groups('pusat')) : ?>
                        <th style="min-width: 140px;" class="d-print-none">Aksi</th>
                    <?php endif; ?>
                </tr>
                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                <?php if (!empty($data_produk)) : ?>
                    <?php foreach ($data_produk as $data) : ?>
                        <tr>
                            <td class="text-center"><?= $nomor++ ?></td>
                            <td class="text-center"><?= $data['kode_produk'] ?></td>
                            <td class="text-center"><?= $data['nama'] ?></td>
                            <td class="text-center">Rp <?= number_format($data['harga_jual'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= $data['nama_kategori'] ?></td>
                            <td class="text-center"><?= $data['nama_supplier'] ?></td>
                            <?php if (in_groups('pusat')) : ?>
                                <td class="text-center d-print-none">
                                    <a href="<?= base_url('produk/edit/' . $data['slug']) ?>" class="badge bg-warning">Edit</a>
                                    <a href="/delete" class="badge bg-danger btn-hapus" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="<?= $data['id'] ?>" data-name="<?= $data['nama'] ?>">
                                        Hapus
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="text-center">
                        <td colspan="12">Data produk tidak ditemukan.</td>
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