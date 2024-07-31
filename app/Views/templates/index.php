<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= env("APP_NAME", "O-Stock") ?> | <?= $title ?></title>
    <!-- CSS files -->
    <link href="<?= base_url('../assets/css/tabler.min.css?1684106062') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/css/tabler-flags.min.css?1684106062') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/css/tabler-payments.min.css?1684106062') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/css/tabler-vendors.min.css?1684106062') ?>" rel="stylesheet" />
    <link href="<?= base_url('../assets/css/demo.min.css?1684106062') ?>" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>

    <!-- jQuery -->
    <script src="<?= base_url('js/code.jquery.com_jquery-3.7.0.min.js') ?>"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="shortcut icon" href="<?= base_url('./assets/img/company/logo.png') ?>" type="image/x-icon">
</head>

<body>
    <script src="<?= base_url('../assets/js/demo-theme.min.js?1684106062') ?>"></script>
    <div class="page">
        <!-- Header -->
        <?= $this->include('partials/header') ?>

        <!-- Navbar -->
        <?= $this->include('partials/navbar') ?>

        <div class="page-wrapper">
            <!-- Page body -->
            <?= $this->renderSection('pageBody'); ?>

            <!-- Footer -->
            <?= $this->include('partials/footer') ?>
        </div>
    </div>
    <!-- Tabler Core -->
    <script src="<?= base_url('../assets/js/tabler.min.js?1684106062') ?>" defer></script>
    <script src="<?= base_url('../assets/js/demo.min.js?1684106062') ?>" defer></script>

    <!-- Tabler Libs -->
    <script src="<?= base_url('../assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?php if (session()->getFlashdata('berhasil')) : ?>
        <script>
            Swal.fire({
                title: "Berhasil",
                text: "<?= session()->getFlashdata('berhasil') ?>",
                icon: "success"
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('gagal')) : ?>
        <script>
            Swal.fire({
                title: "Gagal",
                text: "<?= session()->getFlashdata('gagal') ?>",
                icon: "error"
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')) : ?>
        <script>
            Swal.fire({
                title: "Kesalahan Teknis",
                text: "<?= session()->getFlashdata('warning') ?>",
                icon: "warning"
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')) : ?>
        <script>
            Swal.fire({
                title: "Info",
                text: "<?= session()->getFlashdata('info') ?>",
                icon: "info"
            });
        </script>
    <?php endif; ?>
</body>

</html>