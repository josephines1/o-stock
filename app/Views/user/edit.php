<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards align-items-start g-3">
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body text-center border-bottom">
                        <img style="width:150px; height:150px; object-fit:cover;" src="<?= base_url('./assets/img/user_profile/' . $db['photo_profile']) ?>" alt="<?= $db['fullname'] ? $db['fullname'] : $db['username'] ?>" class="img-thumbnail object-fit-cover" width="150" height="150">
                    </div>
                    <div class="d-flex">
                        <a href="/hapus-photo" class="card-btn text-warning" data-bs-toggle="modal" data-bs-target="#modal-hapus-foto-<?= $db['id'] ?>">
                            Hapus Foto
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-sm-12">
                <form action="<?= base_url('/user/update') ?>" method="post" class="w-100">
                    <?= csrf_field() ?>
                    <input type="hidden" name="username_db" value="<?= $db['username'] ?>">
                    <input type="hidden" name="role_id_db" value="<?= $user_role_id ?>">
                    <div class="card w-100">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Username</label>
                                <input name="username" type="text" class="form-control <?= validation_show_error('username') ? 'is-invalid' : '' ?>" placeholder="username" value="<?= old('username', $db['username']) ?>">
                                <?php if (validation_show_error('username')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('username') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Lengkap</label>
                                <input name="fullname" type="text" class="form-control <?= validation_show_error('fullname') ? 'is-invalid' : '' ?>" placeholder="Nama Lengkap" value="<?= old('fullname', htmlspecialchars($db['fullname'])) ?>">
                                <?php if (validation_show_error('fullname')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('fullname') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Email</label>
                                <input name="email" type="text" class="form-control <?= validation_show_error('email') ? 'is-invalid' : '' ?>" placeholder="email" value="<?= old('email', $db['email']) ?>">
                                <?php if (validation_show_error('email')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" type="text" class="form-select <?= validation_show_error('status') ? 'is-invalid' : '' ?>">
                                    <option value="0" <?= old('status', $db['active']) === "0" ? 'selected' : '' ?>>Tidak Aktif</option>
                                    <option value="1" <?= old('status', $db['active']) === "1" ? 'selected' : '' ?>>Aktif</option>
                                </select>
                                <?php if (validation_show_error('status')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('status') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Role</label>
                                <select name="role" id="role" type="text" class="form-select <?= validation_show_error('role') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Role---</option>
                                    <?php if (!empty($data_role)) : ?>
                                        <?php foreach ($data_role as $option) : ?>
                                            <option value="<?= $option['id'] ?>" <?= old('role', $user_role_id) === $option['id'] ? 'selected' : '' ?>><?= $option['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan role</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('role')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('role') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100" id="cabang" <?= $user_role_id == $cabang_role_id ? '' : 'style="display: none;"' ?>>
                                <label class="form-label">Cabang</label>
                                <select name="cabang" id="cabangSelect" type="text" class="form-select <?= validation_show_error('cabang') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Cabang---</option>
                                    <?php if (!empty($cabang_options)) : ?>
                                        <?php foreach ($cabang_options as $option) : ?>
                                            <option value="<?= $option['id'] ?>" <?= old('cabang', $db['id_kantor']) === $option['id'] ? 'selected' : '' ?>><?= $option['nama'] ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Tidak ada pilihan cabang</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (validation_show_error('cabang')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('cabang') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="<?= base_url('user') ?>" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Box - Hapus Foto -->
<div class="modal modal-blur fade" id="modal-hapus-foto-<?= $db['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <p>Apakah Anda yakin ingin menghapus photo profile user <span class="text-danger bold"><?= $db['fullname'] ? $db['fullname'] : $db['username'] ?></span>?</p>
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
                            <form class="w-100 card-btn text-warning p-0" action="<?= base_url('/hapus-photo') ?>" method="post">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="user_id" value="<?= $db['id'] ?>">
                                <input type="hidden" name="photo_db" value="<?= $db['photo_profile'] ?>">
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
    $(document).ready(function() {
        // Periksa apakah pengguna sebelumnya telah memilih 'cabang' sebagai role
        var validationState = $('#cabangSelect').hasClass('is-invalid');
        if (validationState == true) {
            $('#cabang').show();
        }

        // Tambahkan event listener untuk input select role
        $('#role').change(function() {
            var selectedRole = $(this).val();
            if (selectedRole == <?= $cabang_role_id ?> || validationState) {
                $('#cabang').show();
            } else {
                $('#cabang').hide();
            }
        });

        // Select2 pada cabang
        $('#cabangSelect').select2({
            placeholder: "---Pilih Cabang---",
            allowClear: false,
            width: '100%'
        });
    });
</script>
<?= $this->endSection() ?>