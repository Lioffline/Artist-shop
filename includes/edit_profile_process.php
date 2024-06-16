<?php
session_start();
require_once 'config.php';
require_once 'dbh.inc.php';

// Обработка данных формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    
    // Проверка данных для профиля
    if (isset($_POST["name"]) && isset($_POST["avatar"]) && isset($_POST["description"])) {
        $name = $_POST["name"];
        $avatar = $_POST["avatar"];
        $description = $_POST["description"];
        
        // Проверка занятости имени
        $query = "SELECT * FROM users WHERE NAME = :name AND id != :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $_SESSION["error_message"] = "Name is already taken!";
            header("Location: ../edit_profile.php");
            exit();
        }

        $query = "UPDATE users SET NAME = :name, avatar = :avatar, user_description = :description WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":avatar", $avatar);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
    }

    // Проверка данных для аккаунта
    if (isset($_POST["email"]) && isset($_POST["current_password"])) {
        $email = $_POST["email"];
        $current_password = $_POST["current_password"];
        
        // Проверка текущего пароля
        $query = "SELECT pwd FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($current_password, $result["pwd"])) {
            $_SESSION["error_message"] = "Current password is incorrect!";
            header("Location: ../edit_profile.php");
            exit();
        }
        
        // Проверка занятости почты
        $query = "SELECT * FROM users WHERE email = :email AND id != :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $_SESSION["error_message"] = "Email is already in use!";
            header("Location: ../edit_profile.php");
            exit();
        }
        
        $query = "UPDATE users SET email = :email WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        
        if (!empty($_POST["new_password"])) {
            $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
            $query = "UPDATE users SET pwd = :password WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":password", $new_password);
            $stmt->bindParam(":id", $user_id);
            $stmt->execute();
        }
    }

    header("Location: ../profile.php?user_id=$user_id");
    exit();
}
?>
