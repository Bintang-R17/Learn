<!DOCTYPE html>
<html>
<head>
    <title>Profil <?= htmlspecialchars($user['username']) ?></title>
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
    <h2>Profil: <?= htmlspecialchars($user['username']) ?></h2>
<p>Status: <?= htmlspecialchars($user['status']) ?></p>

<a href="index.php?action=dashboard">Kembali</a>
    <p>Anda sedang melihat profil orang lain.</p>

</body>
</html>
