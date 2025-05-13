<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <?php include __DIR__ . '/partials/template.php';?>


    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0b7dda;
        }
        .back-link {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Edit User</h2>
    <?php if ($dsn && $dsnData = $dsn->fetch_assoc()): ?>
    <form action="index.php?action=updateDosenProcess" method="POST">
        <input type="hidden" name="id" value="<?php echo $dsnData['id']; ?>">
        
        <label for="nama_lengkap">Nama:</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($dsnData['nama_lengkap']); ?>" required>
        
        <label for="mata_kuliah">Mata Kuliah:</label>
        <input type="text" id="mata_kuliah" name="mata_kuliah" value="<?php echo htmlspecialchars($dsnData['mata_kuliah']); ?>" required>
        
        <label for="nip">NIP:</label>
        <input type="text" id="nip" name="nip" value="<?php echo htmlspecialchars($dsnData['nip']); ?>" maxlength="8" required>
        
        <input type="submit" value="Update User">
    </form>
    <?php else: ?>
        <p>User tidak ditemukan.</p>
    <?php endif; ?>
    
    <div class="back-link">
        <a href="index.php?action=listUser">Kembali ke Daftar Dosen</a>
    </div>
</body>
</html>