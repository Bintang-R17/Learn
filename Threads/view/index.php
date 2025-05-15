<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Halaman Utama - Daftar User</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e9ebee; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { text-align: center; }
        .user-list { list-style: none; padding: 0; }
        .user-list li {
            background: #f5f8fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .user-list li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 18px;
        }
        .user-list li a:hover {
            color: #1da1f2; /* Twitter blue */
        }
        .logout-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: #e0245e;
            text-decoration: none;
        }
        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Daftar User Lain</h1>

    <?php if (!empty($users)): ?>
        <ul class="user-list">
            <?php foreach ($users as $user): ?>
                <li>
                    <!-- Link ke profil user lain -->
                    <a href="index.php?action=showProfile&id=<?= htmlspecialchars($user['id']) ?>">
                <?= htmlspecialchars($user['username']) ?>
            </a>

                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada user lain.</p>
    <?php endif; ?>
    <p><a href="index.php?action=profile" class="back-link">Kembali ke Profil Saya</a></p>
    <a href="index.php?action=logout" class="logout-link">Logout</a>
</div>

</body>
</html>
