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
                    <input id="keyword" value="<?= $filter_k ?>" type="search" class="form-control d-inline-block w-9 me-md-3 me-0 mb-md-0 mb-2" placeholder="Search cabang">
                    <?php if (in_groups('pusat')) : ?>
                        <a href="<?= base_url("/cabang/new") ?>" class="btn btn-primary ms-auto ms-md-0 mt-3 mt-md-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            New Cabang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="data-cabang">
                    <div class="card-body">
                        <h3 class="card-title">Data Pusat & Cabang</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th style="min-width: 140px;">Kode</th>
                                    <th style="min-width: 140px;">Nama</th>
                                    <th style="min-width: 140px;">Alamat</th>
                                    <?php if (in_groups('pusat')) : ?>
                                        <th style="min-width: 160px;">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                                <?php if (!empty($data_cabang)) : ?>
                                    <?php foreach ($data_cabang as $data) : ?>
                                        <tr>
                                            <td class="text-center"><?= $nomor++ ?></td>
                                            <td class="text-center">
                                                <?= $data['kode_cabang'] ?>
                                                <?= $data['tipe'] == 'pusat' ? '<span class="badge bg-blue-lt">pusat</span>' : '' ?></td>
                                            <td class="text-center"><?= $data['nama'] ?></td>
                                            <td class="text-center"><?= $data['alamat'] ?></td>
                                            <?php if (in_groups('pusat')) : ?>
                                                <td class="text-center">
                                                    <a href="<?= base_url('cabang/edit/' . $data['slug']) ?>" class="badge bg-warning">Edit</a>
                                                    <a href="/delete" class="badge bg-danger btn-hapus" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="<?= $data['id'] ?>" data-name="<?= $data['nama'] ?>">
                                                        Hapus
                                                    </a>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="text-center">
                                        <td colspan="6">Belum ada data cabang.</td>
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
        $('#keyword').on('keyup', function() {
            $.get('cari-cabang?keyword=' + $('#keyword').val(), function(data) {
                $('#data-cabang').html(data);
            })
        })

        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var nama = $(this).data('name');
            $('#modal-danger').modal('show');
            $('#modal-cabang-name').html(nama);
            // Setelah tombol hapus diklik, Anda dapat menetapkan action form hapus ke URL yang benar
            $('#form-hapus').attr('action', '/cabang/' + id);
        });
    })
</script>
<?= $this->endSection() ?>