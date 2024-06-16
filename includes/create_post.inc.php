<?php
declare(strict_types=1);

require_once 'includes/config.php';
require_once 'includes/dbh.inc.php';

// Проверяем, вошел ли пользователь в систему
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Обработка данных формы, отправленных пользователем
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Проверяем, что все необходимые поля заполнены
    if (isset($_POST['post_title'], $_POST['post_image_url'], $_POST['post_description'], $_POST['post_tags'])) {
        // Получаем данные из формы
        $title = $_POST['post_title'];
        $imageLink = $_POST['post_image_url'];
        $description = $_POST['post_description'];
        $tags = $_POST['post_tags'];
        $creator = $_SESSION['user_id']; // Идентификатор текущего пользователя

        // Вставляем данные в базу данных
        $query = "INSERT INTO posts (title, image_link, short_desc, tags, creator) 
                  VALUES (:title, :image_link, :short_desc, :tags, :creator)";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':image_link', $imageLink);
        $stmt->bindParam(':short_desc', $description);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':creator', $creator);

        $stmt->execute();

        header("Location: index.php");
        exit();
    } else {
    }
}
?>
