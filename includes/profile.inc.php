<?php
// includes/profile.inc.php

require_once 'config.php';
require_once 'dbh.inc.php';
require_once 'main_view.php';
require_once 'signup_view.php';
require_once 'login_view.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

try {
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = null;
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Перенаправляем пользователя на профиль с его идентификатором
header("Location: ../profile.php?user_id=$user_id");
exit();
?>