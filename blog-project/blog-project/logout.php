<?php

require __DIR__ . '/includes/functions.php';
session_destroy();
header('Location: index.php');
exit;
?>






<!DOCTYPE html>
<html lang="ru">
<head>
<title>Мой блог</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="header">
<div class="container">
<h1>🦭 Мой интернет-блог</h1>
<nav class="nav">
<a href="login.php">Войти</a>
<a href="register.php">Регистрация</a>
</nav>
</div>
</header>
<main class="container">
</main>
<footer class="footer">
<div class="container">
<p>Мой блог © 2025 - Практический проект на PHP</p>
</div>
</footer>
</body>
</html>