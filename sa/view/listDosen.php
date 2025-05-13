<?php
include "partials/navbar.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Dosen</title>
    <?php include __DIR__ . '/partials/template.php';?>
    <link rel="stylesheet" href="assets/list.css">
</head>
<body>
    
    <h2>Daftar Dosen</h2>
    
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Data berhasil ditambahkan!</div>
        <?php elseif ($_GET['status'] == 'updated'): ?>
            <div class="alert alert-success">Data berhasil diupdate!</div>
        <?php elseif ($_GET['status'] == 'deleted'): ?>
            <div class="alert alert-success">Data berhasil dihapus!</div>
        <?php endif; ?>
    <?php endif; ?>
    
    <a href="index.php?action=addDosen" class="btn btn-add">Tambah Dosen Baru</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Mata Kuliah</th>
                <th>NIP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            <?php if ($dosen->num_rows > 0): ?>
                <?php while($dsn = $dosen->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $dsn['id']; ?></td>
                    <td><?php echo htmlspecialchars($dsn['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($dsn['mata_kuliah']); ?></td>
                    <td><?php echo htmlspecialchars($dsn['nip']); ?></td>
                    <td>
                        <div class="actions">
                            <a href="index.php?action=editDosen&id=<?php echo $dsn['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="index.php?action=deleteDosen&id=<?php echo $dsn['id']; ?>" 
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