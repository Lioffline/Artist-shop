<?php
// Подключния
require_once 'config.php';
require_once 'dbh.inc.php';

// Удаление поста
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];

        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $postId);

        if ($stmt->execute()) {
            header("Location: ../index.php?delete=success");
            exit();
        } else {
            die("Error deleting the post.");
        }
    }
}
?>
