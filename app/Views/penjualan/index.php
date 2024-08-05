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
                    <input id="keyword" value="<?= $filter_key ?>" type="search" class="form-control d-inline-block w-9 me-0 mb-md-0 mb-2" placeholder="Search invoice / konsumen penjualan">
                    <div class="dropdown ms-0 ms-md-3 mt-3 mt-md-0">
                        <a href="#filter" class="btn btn-secondary-outline text-secondary dropdown-toggle" data-bs-toggle="dropdown">Filter Data</a>
                        <div class="dropdown-menu dropdown-menu-arrow" style="max-width: 350px; min-width: 200px;">
                            <h3 class="dropdown-header">Filters</h3>
                            <div class="row g-1 justify-content-evenly w-100">
                                <div class="w-50 p-2">
                                    <select name="bulan" id="bulan" class="form-select">
                                        <option value="01" <?= ($filter_bul == '01') ? 'selected' : '' ?>>Januari</option>
                                        <option value="02" <?= ($filter_bul == '02') ? 'selected' : '' ?>>Februari</option>
                                        <option value="03" <?= ($filter_bul == '03') ? 'selected' : '' ?>>Maret</option>
                                        <option value="04" <?= ($filter_bul == '04') ? 'selected' : '' ?>>April</option>
                                        <option value="05" <?= ($filter_bul == '05') ? 'selected' : '' ?>>Mei</option>
                                        <option value="06" <?= ($filter_bul == '06') ? 'selected' : '' ?>>Juni</option>
                                        <option value="07" <?= ($filter_bul == '07') ? 'selected' : '' ?>>Juli</option>
                                        <option value="08" <?= ($filter_bul == '08') ? 'selected' : '' ?>>Agustus</option>
                                        <option value="09" <?= ($filter_bul == '09') ? 'selected' : '' ?>>September</option>
                                        <option value="10" <?= ($filter_bul == '10') ? 'selected' : '' ?>>Oktober</option>
                                        <option value="11" <?= ($filter_bul == '11') ? 'selected' : '' ?>>November</option>
                                        <option value="12" <?= ($filter_bul == '12') ? 'selected' : '' ?>>Desember</option>
                                    </select>
                                </div>
                                <div class="w-50 p-2">
                                    <select name="tahun" class="form-select filter_tahun" id="tahun">
                                    </select>
                                </div>
                            </div>
                            <?php if (in_groups('pusat')) : ?>
                                <div class="w-100 p-2">
                                    <select class="form-select" id="cabang">
                                        <?php if (!empty($data_cabang)) : ?>
                                            <option value="0" <?= ($filter_cab == '0') ? 'selected' : '' ?>>Semua Cabang</option>
                                            <?php foreach ($data_cabang as $cabang) : ?>
                                                <option value=<?= $cabang['id'] ?> <?= ($filter_cab == $cabang['id']) ? 'selected' : '' ?>><?= $cabang['nama'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="0" <?= ($filter_cab == "") ? 'selected' : '' ?>>Belum ada Cabang.</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="w-100 p-2">
                                <select class="form-select" id="salesman">
                                    <option value="0" <?= ($filter_sal == '0') ? 'selected' : '' ?>>Semua Salesman</option>
                                    <?php foreach ($data_salesman as $salesman) : ?>
                                        <option value=<?= $salesman['id'] ?> <?= ($filter_sal == $salesman['id']) ? 'selected' : '' ?>><?= $salesman['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="w-100 p-2">
                                <select class="form-select" id="produk">
                                    <option value="0" <?= ($filter_pro == '0') ? 'selected' : '' ?>>Semua Produk</option>
                                    <?php foreach ($data_produk as $produk) : ?>
                                        <option value=<?= $produk['id'] ?> <?= ($filter_pro == $produk['id']) ? 'selected' : '' ?>><?= $produk['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if (!in_groups('pusat')) : ?>
                        <a href="<?= base_url("/penjualan/new") ?>" class="btn btn-primary ms-auto ms-md-3 mt-3 mt-md-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            New Penjualan
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="data-penjualan">
                    <div class="card-body">
                        <h3 class="card-title">Penjualan - <?= $filter_cabang_nama ?>, <?= $filter_salesman_nama ?>, <?= $filter_produk_nama ?><?= $filter_key ? ', Keyword: ' . $filter_key : '' ?> (<?= date('F Y', strtotime($filter_waktu)) ?>)</h3>
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
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen select
        var selectTahuns = document.getElementsByClassName('filter_tahun');

        for (var i = 0; i < selectTahuns.length; i++) {
            var selectTahun = selectTahuns[i];
            var tahunSekarang = new Date().getFullYear();
            for (var tahun = <?= $tahun_mulai ?>; tahun <= tahunSekarang; tahun++) {
                var option = document.createElement('option');
                option.value = tahun;
                option.text = tahun;
                if (tahun == <?= $filter_tah ?>) {
                    option.selected = true;
                }
                selectTahun.add(option);
            }
        }
    });

    $(document).ready(function() {
        $('#keyword').on('keyup', function() {
            $.get('cari-penjualan?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val() + '&salesman=' + $('#salesman').val() + '&produk=' + $('#produk').val() + '&bulan=' + $('#bulan').val() + '&tahun=' + $('#tahun').val(), function(data) {
                $('#data-penjualan').html(data);
            })
        })

        $('#cabang').change(function() {
            $.get('cari-penjualan?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val() + '&salesman=' + $('#salesman').val() + '&produk=' + $('#produk').val() + '&bulan=' + $('#bulan').val() + '&tahun=' + $('#tahun').val(), function(data) {
                $('#data-penjualan').html(data);
            })
        })

        $('#salesman').change(function() {
            $.get('cari-penjualan?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val() + '&salesman=' + $('#salesman').val() + '&produk=' + $('#produk').val() + '&bulan=' + $('#bulan').val() + '&tahun=' + $('#tahun').val(), function(data) {
                $('#data-penjualan').html(data);
            })
        })

        $('#produk').change(function() {
            $.get('cari-penjualan?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val() + '&salesman=' + $('#salesman').val() + '&produk=' + $('#produk').val() + '&bulan=' + $('#bulan').val() + '&tahun=' + $('#tahun').val(), function(data) {
                $('#data-penjualan').html(data);
            })
        })

        $('#bulan').change(function() {
            $.get('cari-penjualan?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val() + '&salesman=' + $('#salesman').val() + '&produk=' + $('#produk').val() + '&bulan=' + $('#bulan').val() + '&tahun=' + $('#tahun').val(), function(data) {
                $('#data-penjualan').html(data);
            })
        })

        $('#tahun').change(function() {
            $.get('cari-penjualan?keyword=' + $('#keyword').val() + '&cabang=' + $('#cabang').val() + '&salesman=' + $('#salesman').val() + '&produk=' + $('#produk').val() + '&bulan=' + $('#bulan').val() + '&tahun=' + $('#tahun').val(), function(data) {
                $('#data-penjualan').html(data);
            })
        })
    })
</script>
<?= $this->endSection() ?>