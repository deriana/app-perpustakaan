<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

include_once("template/header.php");
require_once("function.php");

if (isset($_POST['tambah_user'])) {
    if (tambah_user($_POST) > 0) {
        echo "<script>alert('User berhasil ditambahkan!');</script>";
        echo "<script>window.location.href = 'users.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan user');</script>";
    }
}

if (isset($_POST['ubah_user'])) {
    if (ubah_user($_POST) > 0) {
        echo "<script>alert('User berhasil diubah!');</script>";
        echo "<script>window.location.href = 'users.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah user');</script>";
    }
}

if (isset($_POST['simpan_password'])) {
    if (ganti_password($_POST) > 0) {
        echo "<script>alert('Password berhasil diubah!');</script>";
        echo "<script>window.location.href = 'users.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah password');</script>";
    }
}


?>

<div class="main-panel m-4">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h2 class="card-title mr-5">Users</h2>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahUser">Tambah User</button>
    </div>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <td>Profile Img</td>
                <th>User Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $users = query("SELECT * FROM users");
            foreach ($users as $user): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $user['user_name'] ?></td>
                    <td>
                        <?php if (!empty($user['pf_img'])) : ?>
                            <a href="#" data-toggle="modal" data-target="#fotoModal" data-img="pf_img/<?= htmlspecialchars($user['pf_img']); ?>">
                                <img src="pf_img/<?= htmlspecialchars($user['pf_img']); ?>" alt="profile picture" style="width: 70px; height: auto">
                            </a>
                        <?php else : ?>
                            <span>Tidak Ada Foto</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gantiPassword" data-id="<?= $user['id_user'] ?>">Ganti Password</button>
                        <button type="button" class="btn btn-sm btn-info btn-edit-user"
                            data-toggle="modal"
                            data-target="editUserModal"
                            data-id="<?= $user['id_user']; ?>"
                            data-username="<?= $user['user_name']; ?>"
                            data-role="<?= $user['role']; ?>">
                            Edit User
                        </button>
                        <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')" class="btn btn-sm btn-danger" href="hapus_users.php?id_user=<?= htmlspecialchars($user['id_user']); ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalFoto" src="" alt="foto user" style="width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahUser" tabindex="-1" aria-labelledby="tambahUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahUserLabel">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="user_name">Username</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Profil (Opsional)</label>
                        <input type="file" class="form-control-file" id="foto" name="foto">
                    </div>
                    <button type="submit" class="btn btn-primary" name="tambah_user">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" id="editUserForm">
                    <input type="hidden" id="edit_id_user" name="id_user">
                    <div class="form-group">
                        <label for="edit_user_name">Username</label>
                        <input type="text" class="form-control" id="edit_user_name" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Role</label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="users">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_foto">Foto Profil (Opsional)</label>
                        <input type="file" class="form-control-file" id="edit_foto" name="foto">
                    </div>
                    <button type="submit" class="btn btn-primary" name="ubah_user">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="gantiPassword" tabindex="-1" aria-labelledby="gantiPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gantiPasswordLabel">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="id_user" id="id_users_password">
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-7">
                            <input type="password" class="form-control" id="password" name="user_password" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                            <button type="submit" class="btn btn-primary" name="simpan_password">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#fotoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var imgSrc = button.data('img'); // Get the image URL from data-img
            var modal = $(this);
            modal.find('#modalFoto').attr('src', imgSrc); // Set the modal image source
        });

        $('.btn-edit-user').on('click', function() {
            var id_user = $(this).data('id'); // Mengambil ID user dari tombol

            // Mengisi form modal dengan data user
            $('#edit_id_user').val(id_user); // Set ID correctly
            $('#edit_user_name').val($(this).data('username')); // Populate username
            $('#edit_role').val($(this).data('role')); // Populate role correctly

            // Membuka modal edit
            $('#editUserModal').modal('show'); // Ensure this matches your modal ID
        });


        // Event to open change password modal
        $('#gantiPassword').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var id = button.data('id'); // Ambil ID pengguna dari tombol
            $(this).find('.modal-body #id_users_password').val(id); // Set ID pengguna di modal ganti password
        });

    });
</script>

<?php
include_once("template/footer.php");
