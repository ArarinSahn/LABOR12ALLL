<?php
// debug.php
echo "<h1>Тест системы</h1>";

// Тест 1: Работает ли PHP?
echo "<p>PHP работает: ДА</p>";

// Тест 2: Сессия
session_start();
echo "<p>Сессия стартовала: ДА</p>";
echo "<p>ID пользователя в сессии: " . ($_SESSION['user_id'] ?? 'НЕТ') . "</p>";
echo "<p>Имя пользователя: " . ($_SESSION['username'] ?? 'НЕТ') . "</p>";

// Тест 3: Проверка папок
echo "<p>Папка data существует: " . (file_exists(__DIR__ . '/data') ? 'ДА' : 'НЕТ') . "</p>";
echo "<p>Можно писать в data: " . (is_writable(__DIR__ . '/data') ? 'ДА' : 'НЕТ') . "</p>";

// Тест 4: Проверка файлов
$files = ['users.json', 'posts.json', 'comments.json'];
foreach ($files as $file) {
    $path = __DIR__ . '/data/' . $file;
    $exists = file_exists($path);
    echo "<p>Файл $file существует: " . ($exists ? 'ДА' : 'НЕТ') . "</p>";
    if ($exists) {
        echo "<p>Размер $file: " . filesize($path) . " байт</p>";
        echo "<pre>" . htmlspecialchars(file_get_contents($path)) . "</pre>";
    }
}
?>