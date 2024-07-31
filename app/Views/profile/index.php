<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row g-3 align-items-stretch">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <span class="avatar avatar-xl mb-3 rounded" style="background-image: url(<?= base_url('/assets/img/user_profile/' . $user['photo_profile']) ?>); width:150px; height:150px; object-fit:cover;"></span>
                        <h3 class="m-0 mb-1"><?= $user['fullname'] ? $user['fullname'] : $user['username'] ?></h3>
                        <div class="text-muted"><?= $user['kode_kantor'] ?> - <?= $user['nama_kantor'] ?></div>
                        <div class="mt-3">
                            <span class="badge <?php
                                                if ($user['role'] === 'pusat') {
                                                    echo 'bg-green-lt';
                                                } else {
                                                    echo 'bg-purple-lt';
                                                } ?>">
                                <?= $user['role'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="<?= base_url('/logout') ?>" class="card-btn text-danger" data-bs-toggle="modal" data-bs-target="#logout-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout me-2 text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                <path d="M9 12h12l-3 -3" />
                                <path d="M18 15l3 -3" />
                            </svg>
                            Logout</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Profil
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Username</th>
                                    <td><?= $user['username'] ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= $user['email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Cabang</th>
                                    <td><?= $user['kode_kantor'] ?> - <?= $user['nama_kantor'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="card-title">Keamanan</div>
                    </div>
                    <div class="d-flex">
                        <a href="<?= base_url('profile/ubah-password') ?>" class="card-btn text-warning">
                            <svg xmlns=" http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-password-user me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 17v4" />
                                <path d="M10 20l4 -2" />
                                <path d="M10 18l4 2" />
                                <path d="M5 17v4" />
                                <path d="M3 20l4 -2" />
                                <path d="M3 18l4 2" />
                                <path d="M19 17v4" />
                                <path d="M17 20l4 -2" />
                                <path d="M17 18l4 2" />
                                <path d="M9 6a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path d="M7 14a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2" />
                            </svg>
                            Ubah Password
                        </a>
                        <a href="<?= base_url('/profile/edit') ?>" class="card-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-edit me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                            </svg>
                            Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ubah Email Modal -->
<div class="modal modal-blur fade" id="email-modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                <h3>Yakin ingin mengganti email Anda?</h3>
                <div class="text-muted">Lanjutkan untuk memperbarui email Anda dengan yang terbaru. <br><br>Kami akan mengirimkan token konfirmasi ke alamat email Anda untuk menyelesaikan proses perubahan alamat email.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="<?= base_url('profile') ?>" class="btn w-100" data-bs-dismiss="modal">
                                Batal
                            </a>
                        </div>
                        <div class="col">
                            <form action="<?= base_url('/send-email-token') ?>" method="post" class="d-inline w-100">
                                <?= csrf_field() ?>
                                <input type="hidden" name="email" value="<?= user()->email ?>">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    Ubah Email
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal modal-blur fade" id="logout-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="modal-title">
                    <h1>Logout</h1>
                </div>
                <div>
                    <p class="mb-1">Pekerjaan selesai! Apakah Anda yakin ingin logout?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                <a href="<?= base_url('logout') ?>" class="btn btn-danger">Ya, logout</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>