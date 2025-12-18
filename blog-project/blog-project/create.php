<?php
require __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $mediaPath = null;

    if (empty($title) || empty($content)) {
        $error = 'Заполните заголовок и содержимое записи';
    } elseif (strlen($title) < 5) {
        $error = 'Заголовок должен содержать минимум 5 символов';
    } elseif (strlen($content) < 10) {
        $error = 'Содержимое должно содержать минимум 10 символов';
    }
    if (!$error && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            $error = 'Размер файла не должен превышать 2Mb';
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $file['tmp_name']);
            finfo_close($fileInfo);

            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExts)) {
                $error = 'Разрешены только изображения JPG, PNG, GIF';
            } else {
                $filename = uniqid('img_', true) . '.' . $ext;
                $uploadPath = UPLOADS_DIR . '/' . $filename;
                if (!is_dir(UPLOADS_DIR)) {
                    mkdir(UPLOADS_DIR, 0755, true);
                }

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $mediaPath = 'uploads/' . $filename;
                } else {
                    $error = 'Ошибка при загрузке файла';
                }
            }
        }
    }


    if (!$error) {
        $newPost = [
            'id' => generateId(),
            'title' => $title,
            'content' => $content,
            'author_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'], // для удобного вывода
            'created_at' => date('Y-m-d H:i:s'),
            'media' => $mediaPath ? [$mediaPath] : []
        ];
        $posts = getPosts();
        $posts[] = $newPost;

        if (saveData('posts.json', $posts)) { // Перенаправляем на страницу просмотра поста
            header('Location: post.php?id=' . $newPost['id']);
            exit;
        } else {
            $error = 'Ошибка при сохранении записи';
        }
    }
}

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
            <h1>✍️ Создать новый пост</h1>
            <nav class="nav">
                <a href="index.php">На главную</a>
                <a href="logout.php">Выход</a>
                <a href="post.php">Посмотреть посты</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <h1>Новый пост в блоге</h1>
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form class="form-group" method="post" enctype="multipart/form-data">
            <label class="form-group">Заголовок записи:</label>
            <input class="form-group" type="text" name="title">
            <br>
            <label class="form-group">Содержимое:</label>
            <textarea class="form-group" name="content"></textarea>
            <br>
            <label class="form-group">Изображение (необязательно):</label>
            <input class="form-group" type="file" name="image">
            <br>
            <input class="btn btn-primary" type="submit" value="Опубликовать пост">
        </form>

    </main>
    <footer class="footer">
        <div class="container">
            <p>Мой блог © 2025 - Практический проект на PHP</p>
        </div>
    </footer>
</body>

</html>