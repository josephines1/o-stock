<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/profile/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-start">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center border-bottom">
                            <img src="<?= base_url('./assets/img/user_profile/' . user()->photo_profile) ?>" alt="<?= user()->username ?>" class="img-thumbnail" id="img-preview" style="width:150px; height:150px; object-fit:cover;">
                        </div>
                        <div class="d-flex">
                            <a href="/profile/hapus-foto" class="card-btn text-warning" data-bs-toggle="modal" data-bs-target="#modal-hapus-foto">
                                Hapus Foto
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="oldFoto" value="<?= user()->photo_profile ?>">
                            <div class="mb-3 w-100">
                                <label for="input-foto-profile-baru" class="form-label">Upload Foto Profile Baru</label>
                                <input onchange="inputImgPreview()" type="file" class="form-control <?= validation_show_error('foto') ? 'is-invalid' : '' ?>" name="foto" id="input-foto-profile-baru">
                                <?php if (validation_show_error('foto')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('foto') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control <?= validation_show_error('username') ? 'is-invalid' : '' ?>" placeholder="username" value="<?= old('username', user()->username) ?>">
                                <?php if (validation_show_error('username')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('username') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="fullname" class="form-control <?= validation_show_error('fullname') ? 'is-invalid' : '' ?>" placeholder="Nama Lengkap" value="<?= old('fullname', user()->fullname) ?>">
                                <?php if (validation_show_error('fullname')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('fullname') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control <?= validation_show_error('email') ? 'is-invalid' : '' ?>" placeholder="Email" value="<?= old('email', user()->email) ?>">
                                <?php if (validation_show_error('email')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('profile') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Box - Hapus Foto -->
<div class="modal modal-blur fade" id="modal-hapus-foto" tabindex="-1" role="dialog" aria-hidden="true">
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
                <h3>Hapus Foto?</h3>
                <div class="text-muted">
                    <p>Apakah Anda yakin ingin menghapus photo profile Anda?</p>
                    <p>Aksi ini akan langsung menghapus foto tanpa persetujuan tombol simpan perubahan pada halaman edit.</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Batal
                            </a></div>
                        <div class="col">
                            <form class="w-100 card-btn text-warning p-0" action="<?= base_url('/profile/hapus-foto') ?>" method="post">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="user_id" value="<?= user_id() ?>">
                                <input type="hidden" name="photo_db" value="<?= user()->photo_profile ?>">
                                <button class="btn btn-danger w-100 border-0" name="hapus-photo" type="submit">
                                    Hapus Foto
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
    function inputImgPreview() {
        const inputFotoProfileBaru = document.getElementById('input-foto-profile-baru');
        const imgPreview = document.getElementById('img-preview');

        const $fileFoto = new FileReader();
        $fileFoto.readAsDataURL(inputFotoProfileBaru.files[0]);
        $fileFoto.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>
<?= $this->endSection() ?>