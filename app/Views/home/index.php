<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<style>
  .parent_date {
    display: grid;
    grid-template-columns: repeat(8, auto);
    font-size: 20px;
    text-align: center;
    justify-content: center;
  }

  .parent_clock {
    display: grid;
    grid-template-columns: repeat(5, auto);
    font-size: 60px;
    font-weight: bold;
    text-align: center;
    justify-content: center;
  }

  #detik {
    opacity: 50%;
  }
</style>
<div class="page-body">
  <div class="container-xl d-flex flex-column justify-content-center">
    <div class="row mb-3 g-3">
      <div class="col-12">
        <div class="row row-deck row-cards mb-3">
          <div class="col-12">
            <div class="card text-blue p-3">
              <div class="card-body">
                <div class="parent_date">
                  <div id="hari"></div>
                  <div> , </div>
                  <div class="ms-1"></div>
                  <div id="tanggal"></div>
                  <div class="ms-1"></div>
                  <div id="bulan"></div>
                  <div class="ms-1"></div>
                  <div id="tahun"></div>
                </div>
                <div class="parent_clock">
                  <div id="jam"></div>
                  <div> : </div>
                  <div id="menit"></div>
                  <div> : </div>
                  <div id="detik"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row row-deck row-cards">
          <div class="col-12">
            <div class="row row-cards">
              <div class="col-lg-3 col-12">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-teal text-white avatar">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-hierarchy">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M5 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M19 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M6.5 17.5l5.5 -4.5l5.5 4.5" />
                            <path d="M12 7l0 6" />
                          </svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          <?= $totalCabang ?>
                        </div>
                        <div class="text-muted">
                          Cabang
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-12">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-orange text-white avatar">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                            <path d="M12 12l8 -4.5" />
                            <path d="M12 12l0 9" />
                            <path d="M12 12l-8 -4.5" />
                          </svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          <?= $totalProduk ?>
                        </div>
                        <div class="text-muted">
                          Produk
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-12">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-pink text-white avatar">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                          </svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          <?= $totalKonsumen ?>
                        </div>
                        <div class="text-muted">
                          Konsumen
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-12">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-green text-white avatar">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                            <path d="M12 17v1m0 -8v1" />
                          </svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          <?= $totalPenjualan ?>
                        </div>
                        <div class="text-muted">
                          Penjualan
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-3 g-3">
      <div class="col-lg-6 col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Penjualan Per Hari</h3>
          </div>
          <div class="card-body">
            <div id="chart-daily-area" class="chart-lg"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Penjualan Per Bulan</h3>
          </div>
          <div class="card-body">
            <div id="chart-monthly-area" class="chart-lg"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  window.setTimeout('waktuDashboard()', 1000);

  function waktuDashboard() {
    const waktu = new Date();

    setTimeout('waktuDashboard()', 1000);

    nama_bulan = [
      'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    ];

    nama_hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];

    hari = document.getElementById('hari');
    tanggal = document.getElementById('tanggal');
    bulan = document.getElementById('bulan');
    tahun = document.getElementById('tahun');
    jam = document.getElementById('jam');
    menit = document.getElementById('menit');
    detik = document.getElementById('detik');

    if (tanggal && bulan && tahun && jam && menit && detik) {
      hari.innerHTML = nama_hari[waktu.getDay()];
      tanggal.innerHTML = waktu.getDate();
      bulan.innerHTML = nama_bulan[waktu.getMonth()];
      tahun.innerHTML = waktu.getFullYear();
      jam.innerHTML = waktu.getHours();
      menit.innerHTML = waktu.getMinutes();
      detik.innerHTML = waktu.getSeconds();
    }
  }

  document.addEventListener("DOMContentLoaded", function() {
    // Apex Chart Daily
    window.ApexCharts && (new ApexCharts(document.getElementById('chart-daily-area'), {
      chart: {
        type: "area",
        fontFamily: 'inherit',
        height: 240,
        parentHeightOffset: 0,
        toolbar: {
          show: false,
        },
        animations: {
          enabled: false
        },
      },
      dataLabels: {
        enabled: false,
      },
      fill: {
        opacity: .16,
        type: 'solid'
      },
      stroke: {
        width: 2,
        lineCap: "round",
        curve: "smooth",
      },
      series: [{
        name: "Data Penjualan",
        data: [
          <?php echo implode(", ", $penjualanLastSevenDays); ?>
        ]
      }],
      tooltip: {
        theme: 'dark'
      },
      grid: {
        padding: {
          top: -20,
          right: 0,
          left: -4,
          bottom: -4
        },
        strokeDashArray: 4,
      },
      xaxis: {
        labels: {
          padding: 0,
        },
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false,
        },
        type: 'datetime',
      },
      yaxis: {
        labels: {
          padding: 4
        },
      },
      labels: [
        <?php foreach ($chartDate as $index => $date) {
          echo "'$date'";
          if ($index < count($chartDate) - 1) {
            echo ", ";
          }
        } ?>
      ],
      colors: [tabler.getColor("primary")],
      legend: {
        show: true,
        position: 'bottom',
        offsetY: 12,
        markers: {
          width: 10,
          height: 10,
          radius: 100,
        },
        itemMargin: {
          horizontal: 8,
          vertical: 8
        },
      },
    })).render();

    // Apex Chart Monthly
    window.ApexCharts && (new ApexCharts(document.getElementById('chart-monthly-area'), {
      chart: {
        type: "area",
        fontFamily: 'inherit',
        height: 240,
        parentHeightOffset: 0,
        toolbar: {
          show: false,
        },
        animations: {
          enabled: false
        },
      },
      dataLabels: {
        enabled: false,
      },
      fill: {
        opacity: .16,
        type: 'solid'
      },
      stroke: {
        width: 2,
        lineCap: "round",
        curve: "smooth",
      },
      series: [{
        name: "Data Penjualan",
        data: [
          <?php echo implode(", ", $penjualanEachMonths); ?>
        ]
      }],
      tooltip: {
        theme: 'dark'
      },
      grid: {
        padding: {
          top: -20,
          right: 0,
          left: -4,
          bottom: -4
        },
        strokeDashArray: 4,
      },
      xaxis: {
        labels: {
          padding: 0,
        },
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false,
        },
        type: 'datetime',
      },
      yaxis: {
        labels: {
          padding: 4
        },
      },
      labels: [
        <?php foreach ($chartMonths as $index => $date) {
          echo "'$date'";
          if ($index < count($chartMonths) - 1) {
            echo ", ";
          }
        } ?>
      ],
      colors: [tabler.getColor("purple")],
      legend: {
        show: true,
        position: 'bottom',
        offsetY: 12,
        markers: {
          width: 10,
          height: 10,
          radius: 100,
        },
        itemMargin: {
          horizontal: 8,
          vertical: 8
        },
      },
    })).render();
  });
</script>
<?= $this->endSection() ?>