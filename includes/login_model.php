<?php

declare(strict_types = 1);

// Получение пользователя
function get_user(object $pdo, string $username)
{
    $query = "SELECT * FROM users WHERE NAME = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results;
}