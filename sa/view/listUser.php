<?php
include "partials/navbar.php";

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User</title>
    <?php include __DIR__ . '/partials/template.php';?>
    <style>
        
    </style>
    <link rel="stylesheet" href="assets/list.css">
</head>
<body>
    <h2>Daftar Mahasiswa</h2>
    
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Data berhasil ditambahkan!</div>
        <?php elseif ($_GET['status'] == 'updated'): ?>
            <div class="alert alert-success">Data berhasil diupdate!</div>
        <?php elseif ($_GET['status'] == 'deleted'): ?>
            <div class="alert alert-success">Data berhasil dihapus!</div>
        <?php endif; ?>
    <?php endif; ?>
    
    <a href="index.php?action=addUser" class="btn btn-add">Tambah User Baru</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Angkatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users->num_rows > 0): ?>
                <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['nama']); ?></td>
                    <td><?php echo htmlspecialchars($user['jurusan']); ?></td>
                    <td><?php echo htmlspecialchars($user['angkatan']); ?></td>
                    <td>
                        <div class="actions">
                            <a href="index.php?action=editUser&id=<?php echo $user['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="index.php?action=deleteUser&id=<?php echo $user['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data user</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>