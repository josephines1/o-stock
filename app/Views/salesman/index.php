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
                    <input id="keyword" value="<?= $filter_k ?>" type="search" class="form-control d-inline-block w-9 me-md-3 me-0 mb-md-0 mb-2" placeholder="Search salesman">
                    <?php if (in_groups('pusat')) : ?>
                        <div class="w-100 mb-md-0 mb-2 me-md-3">
                            <select class="form-select" id="cabang">
                                <option value="0" <?= ($filter_c == '0') ? 'selected' : '' ?>>Semua Cabang</option>
                                <?php foreach ($data_cabang as $cabang) : ?>
                                    <option value=<?= $cabang['id'] ?> <?= ($filter_c == $cabang['id']) ? 'selected' : '' ?>><?= $cabang['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <a href="<?= base_url("/salesman/new") ?>" class="btn btn-primary ms-auto ms-md-0 mt-3 mt-md-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        New Salesman
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="data-salesman">
                    <div class="card-body">
                        <h3 class="card-title">Salesman - <?= $filter_cabang_nama ?></h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th style="min-width: 140px;">Nama</th>
                                    <th style="min-width: 140px;">Cabang</th>
                                    <th style="min-width: 140px;">Nomor Telepon</th>
                                    <th>Penjualan Bulan Ini</th>
                                    <?php if (in_groups('pusat')) : ?>
                                        <th style="min-width: 160px;">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                                <?php if (!empty($data_salesman)) : ?>
                                    <?php foreach ($data_salesman as $data) : ?>
                                        <tr>
                                            <td class="text-center"><?= $nomor++ ?></td>
                                            <td class="text-center"><?= $data['nama'] ?></td>
                                            <td class="text-center"><?= $data['nama_cabang'] ?></td>
                                            <td class="text-center"><?= $data['no_telp'] ?></td>
                                            <td class="text-center"><?= $data['total_penjualan'] ?></td>
                                            <?php if (in_groups('pusat')) : ?>
                                                <td class="text-center">
                                                    <a href="<?= base_url('salesman/edit/' . $data['slug']) ?>" class="badge bg-warning">Edit</a>
                                                    <a href="/delete" class="badge bg-danger btn-hapus" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="<?= $data['id'] ?>" data-name="<?= $data['nama'] ?>">
                                                        Hapus
                                                    </a>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="text-center">
                                        <td colspan="6">Belum ada data salesman.</td>
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

<!-- Modal Box - Delete -->
<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                </svg>
                <h3>Hapus?</h3>
                <div class="text-muted">
                    <p>Apakah Anda yakin ingin menghapus data salesman <strong><span id="modal-salesman-name" class="text-danger">ini</span></strong>?</p>
                    <p> Data penjualan salesman ini juga akan terhapus dan <strong>TIDAK DAPAT DIKEMBALIKAN.</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Batal
                            </a></div>
                        <div class="col">
                            <form action="" method="post" class="d-inline" id="form-hapus">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger w-100">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        <?php if (in_groups('pusat')) : ?>
            $('#keyword').on('keyup', function() {
                $.get('cari-salesman?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val(), function(data) {
                    $('#data-salesman').html(data);
                })
            })

            $('#cabang').change(function() {
                $.get('cari-salesman?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val(), function(data) {
                    $('#data-salesman').html(data);
                })
            })
        <?php else : ?>
            $('#keyword').on('keyup', function() {
                $.get('cari-salesman?keyword=' + $('#keyword').val(), function(data) {
                    $('#data-salesman').html(data);
                })
            })
        <?php endif; ?>

        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var nama = $(this).data('name');
            $('#modal-danger').modal('show');
            $('#modal-salesman-name').html(nama);
            $('#form-hapus').attr('action', '/salesman/' + id);
        });
    });
</script>
<?= $this->endSection() ?>