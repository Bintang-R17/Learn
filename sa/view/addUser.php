<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
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
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Tambah User Baru</h2>
    <form action="index.php?action=addUserProcess" method="POST">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>
        
        <label for="jurusan">Jurusan:</label>
        <input type="text" id="jurusan" name="jurusan" required>
        
        <label for="angkatan">Angkatan:</label>
        <input type="text" id="angkatan" name="angkatan" maxlength="4" required>
        
        <input type="submit" value="Tambah User">
    </form>
    
    <div class="back-link">
        <a href="index.php?action=listDosen">Kembali ke Daftar Dosen</a>
    </div>
</body>
</html>