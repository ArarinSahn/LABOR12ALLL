<?php
require __DIR__ . '/includes/functions.php';

$error = '';
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $usersData = loadData('users.json');
    $foundUser = null;
    
    foreach ($usersData as $user) {
        if ($user['username'] === $username) {
            $foundUser = $user;
            break;
        }
    }
    if ($foundUser && password_verify( $password, $foundUser['password_hash'])) {
        $_SESSION['user_id'] = $foundUser['id'];
        $_SESSION['username'] = $foundUser['username'];
        header('Location: index.php');
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
     <link rel="stylesheet" href="css/style.css">
    <title>Мой блог</title>
</head>

<body>
    <header class="header">
        <div class="container">
            <h1>🔐 Вход в блог</h1>
            <nav class="nav">
                <a href="index.php">На главную</a>
                <a href="register.php">Регистрация</a>
            </nav>
        </div>
    </header>
    <main class="container ">
              <?php if ($error): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" class ="form-group ">
            <label class="form-group">Имя пользователя:</label>
            <input  class="form-group" type="text" name="username" >
            <BR>
            <label class="form-group" >Пароль:</label>
            <input class="form-group" type="password" name="password">
            <BR>
            <input class="btn btn-primary" type="submit">
        </form>
        <h4>Нет аккаунта?</h4>
        <a href="register.php">Зарегистрируйтесь</a>
    </main>
    <footer class="footer">
        <div class="container">
            <p>Мой блог © 2025 - Практический проект на PHP</p>
        </div>
    </footer>
</body>

</html>