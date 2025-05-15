<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Profil User</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f9fa; padding: 20px; }
        .profile-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 8px #ccc;
        }
        h2 { margin-top: 0; }
        form {
            margin-top: 15px;
        }
        label {
            display: block;
            margin: 10px 0 5px 0;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #1da1f2;
            border: none;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button.delete {
            background-color: #e0245e;
        }
        button:hover {
            opacity: 0.9;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #333;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Profil User: <?= htmlspecialchars($user['username']) ?></h2>

    <!-- Form Edit Profile -->
    <form method="post" action="index.php?action=updateUser">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>" />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required />

        <label for="status">Status</label>
        <input type="text" id="status" name="status" value="<?= htmlspecialchars($user['status']) ?>" required />

        <button type="submit">Update Profil</button>
    </form>

    <!-- Form Delete User -->
    <form method="post" action="index.php?action=deleteUser" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>" />
        <button type="submit" class="delete">Hapus User</button>
    </form>

    <a href="index.php?action=index" class="back-link">&larr; Kembali ke Daftar User</a>
</div>

</body>
</html>
