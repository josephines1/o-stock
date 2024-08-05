<div class="card" id="data-user">
    <div class="card-body">
        <h3 class="card-title">User - <?= $filter_role ?>, <?= $filter_cabang ?><?= $filter_keyword ? ', Keyword: ' . $filter_keyword : '' ?></h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width: 140px;">Username</th>
                    <th style="min-width: 140px;">Nama Lengkap</th>
                    <th style="min-width: 160px;">Email</th>
                    <th style="min-width: 140px;">Role</th>
                    <th style="min-width: 140px;">Kantor</th>
                    <?php if (in_groups('pusat')) : ?>
                        <th style="min-width: 140px;">Aksi</th>
                    <?php endif; ?>
                </tr>
                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                <?php if (!empty($data_users)) : ?>
                    <?php foreach ($data_users as $data) : ?>
                        <tr>
                            <td class="text-center"><?= $nomor++ ?></td>
                            <td class="text-center"><?= $data['username'] ?></td>
                            <td class="text-center"><?= $data['fullname'] ? $data['fullname'] : '-' ?></td>
                            <td class="text-center"><?= $data['email'] ?></td>
                            <td class="text-center"><span class="badge 
                                                <?php
                                                if ($data['role'] === 'pusat') {
                                                    echo 'bg-green-lt';
                                                } else if ($data['role'] === 'cabang') {
                                                    echo 'bg-purple-lt';
                                                } ?>">
                                    <?= $data['role'] ?>
                                </span>
                            </td>
                            <td class="text-center"><?= $data['nama_kantor'] ?></td>
                            <?php if (in_groups('pusat')) : ?>
                                <td class="text-center">
                                    <a href="<?= base_url('user/edit/' . $data['username']) ?>" class="badge bg-warning">Edit</a>
                                    <a href="/delete" class="badge bg-danger btn-hapus" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="<?= $data['id'] ?>" data-name="<?= $data['username'] ?>">
                                        Hapus
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="text-center">
                        <td colspan="7">Belum ada data user.</td>
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