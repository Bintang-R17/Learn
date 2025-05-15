<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; }
        .container { max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box;
            border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%;
            cursor: pointer;
        }
        button:hover { background-color: #45a049; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?action=loginProcess">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
