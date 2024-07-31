<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar d-print-none">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item <?= $title == 'Home' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url() ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Home
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown <?= $group == 'data' ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" href="#data" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0" />
                                    <path d="M4 6v6a8 3 0 0 0 16 0v-6" />
                                    <path d="M4 12v6a8 3 0 0 0 16 0v-6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Data
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item <?= $title == 'Salesman' ? 'active' : '' ?>" href="<?= base_url('/salesman') ?>">
                                        Salesman
                                    </a>
                                    <a class="dropdown-item <?= $title == 'Konsumen' ? 'active' : '' ?>" href="<?= base_url('/konsumen') ?>">
                                        Konsumen
                                    </a>
                                    <?php if (in_groups('pusat')) : ?>
                                        <a class="dropdown-item <?= $title == 'Cabang' ? 'active' : '' ?>" href="<?= base_url('/cabang') ?>">
                                            Pusat & Cabang
                                        </a>
                                        <a class="dropdown-item <?= $title == 'User' ? 'active' : '' ?>" href="<?= base_url('/user') ?>">
                                            User
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?= $group == 'resources' ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" href="#resources" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M12 12l0 9" />
                                    <path d="M12 12l-8 -4.5" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Resources
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item <?= $title == 'Kategori' ? 'active' : '' ?>" href="<?= base_url('/kategori') ?>">
                                        Kategori Produk
                                    </a>
                                    <a class="dropdown-item <?= $title == 'Supplier' ? 'active' : '' ?>" href="<?= base_url('/supplier') ?>">
                                        Supplier
                                    </a>
                                    <a class="dropdown-item <?= $title == 'Produk' ? 'active' : '' ?>" href="<?= base_url('/produk') ?>">
                                        Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item <?= $group == 'penjualan' ? 'active' : '' ?>">
                        <a class=" nav-link <?= $title == 'Penjualan' ? 'active' : '' ?>" href="<?= base_url('/penjualan') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-location-dollar">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M13.08 20.162l-3.08 -6.162l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5l-2.55 7.063" />
                                    <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                    <path d="M19 21v1m0 -8v1" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Penjualan
                            </span>
                        </a>
                    </li>
                    <?php if (in_groups('pusat')) : ?>
                        <li class="nav-item <?= $group == 'alokasi' ? 'active' : '' ?>">
                            <a class="nav-link <?= $title == 'Alokasi' ? 'active' : '' ?>" href="<?= base_url('/alokasi') ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-location-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 18l-2 -4l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5l-3.361 9.308" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Alokasi
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item <?= $group == 'kartu_stok' ? 'active' : '' ?>">
                        <a class="nav-link <?= $title == 'Kartu Stok' ? 'active' : '' ?>" href="<?= base_url('kartu-stok') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/ghost -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-report-analytics">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M9 17v-5" />
                                    <path d="M12 17v-1" />
                                    <path d="M15 17v-3" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Kartu Stok
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>