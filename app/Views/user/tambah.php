<?= $this->extend('templates/index') ?>

<?= $this->section('pageBody') ?>
<style>
    .icon-tabler-info-circle {
        transition: transform 0.3s ease-in-out;
        cursor: pointer;
    }

    .icon-tabler-info-circle:hover {
        transform: translateY(-3px);
    }
</style>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('/user/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row row-deck row-cards align-items-stretch">
                <div class="col-lg-6 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 w-100">
                                <label class="form-label">Username</label>
                                <input name="username" type="text" class="form-control <?= validation_show_error('username') ? 'is-invalid' : '' ?>" placeholder="username" value="<?= old('username') ?>" autofocus>
                                <?php if (validation_show_error('username')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('username') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Nama Lengkap</label>
                                <input name="fullname" type="text" class="form-control <?= validation_show_error('fullname') ? 'is-invalid' : '' ?>" placeholder="Nama Lengkap" value="<?= old('fullname') ?>">
                                <?php if (validation_show_error('fullname')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('fullname') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Email</label>
                                <input name="email" type="text" class="form-control <?= validation_show_error('email') ? 'is-invalid' : '' ?>" placeholder="Email" value="<?= old('email') ?>">
                                <?php if (validation_show_error('email')) : ?>
                                    <div class="invalid-feedback">
                                        <?= validation_show_error('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-label">Role</label>
                                <select name="role" id="role" type="text" class="form-select <?= validation_show_error('role') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Role---</option>
                                    <?php if (!empty($data_role)) : ?>
                                        <?php foreach ($data_role as $option) : ?>
                                            <option value="<?= $option['id'] ?>" <?= old('role') === $option['id'] ? 'selected' : '' ?>><?= $option['name'] ?></option>
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
                            <div class="mb-3 w-100" id="cabang" style="display: none;">
                                <label class="form-label">Cabang</label>
                                <select name="cabang" id="cabangSelect" type="text" class="form-select <?= validation_show_error('cabang') ? 'is-invalid' : '' ?>">
                                    <option value="">---Pilih Cabang---</option>
                                    <?php if (!empty($cabang_options)) : ?>
                                        <?php foreach ($cabang_options as $option) : ?>
                                            <option value="<?= $option['id'] ?>" <?= old('cabang') === $option['id'] ? 'selected' : '' ?>><?= $option['nama'] ?></option>
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
                                <button type="submit" class="btn btn-primary ms-auto">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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