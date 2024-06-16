<?php
require_once 'config.php';
require_once 'dbh.inc.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Получаем идентификатор пользователя из сессии
    $logged_in_user_id = $_SESSION['user_id'];

    try {
        $query = "DELETE FROM users WHERE id = :user_id";
        
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        // Проверяем, совпадает ли идентификатор пользователя в сессии с идентификатором удаленного пользователя
        if ($logged_in_user_id == $user_id) {
            // Если да, то выходим из системы
            session_destroy();
        }

        header("Location: ../index.php"); 
    } catch (PDOException $e) {
        die("Ошибка при удалении профиля: ". $e->getMessage());
    }
}
?>