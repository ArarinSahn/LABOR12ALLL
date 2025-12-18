<?php
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/User.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
    if (
        empty($username) || empty($email) || empty($password) || empty($password_confirm)
    ) {
        $error = 'Заполните все обязательные поля';
    } elseif (strlen($username) < 3) {
        $error = 'Имя пользователя должно содержать минимум 3 символа';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен содержать минимум 6 символов';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают';
    } else {

        $users = loadData('users.json'); // сохраняем юзера и ищем совпадения с данными
        foreach ($users as $user) {
            if ($user['username'] === $username) { // если че сохраняем и выводим сообщение 
                $error = 'Пользователь с таким именем уже существует';
                break;
            }
        }
    }
    if (!$error) {
        $usersData = [];
        $user = new User($username, $email, $password);
        $usersData[] = $user->toArray();
        if (saveData('users.json', $usersData)) {
            header('Location: index.php');
        } else {
            $error = 'Ошибка при сохранении данных';
        }
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
            <h1>📝 Регистрация в блоге</h1>
            <nav class="nav">
                <a href="index.php">На главную</a>
                
            </nav>
        </div>
    </header>
    <main class="container">
        <h1 >Создание нового аккаунта</h1>
        <?php if ($error): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" class ="form-group  ">
            <label class="form-group">Имя пользователя: </label>
            <input class="form-group" type="text" name="username">
            <small>Минимум 3 символа</small>
            <br>
            <label class="form-group">Email: </label>
            <input class="form-group" type="email" name="email">
            <br>
            <label class="form-group"> Пароль: </label>
            <input class="form-group" type="password" name="password">
            <small>Минимум 6 символа</small>
            <br>
            <label class="form-group">Подтверждение пароля: </label>
            <input class="form-group" type="password" name="password_confirm">
            <br>
            <input  class="btn btn-primary" type="submit" value="Зарегистрироваться">
        </form>
        <h4>Уже есть аккаунт?</h4>
        <a href="login.php">Войдите</a>
    </main>
    <footer class="footer">
        <div class="container">
            <p>Мой блог © 2025 - Практический проект на PHP</p>
        </div>
    </footer>
</body>

</html>