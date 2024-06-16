<?php
declare(strict_types = 1);

// Проверка занятости имени нового пользователя
function get_username(object $pdo, string $username)
{
    $query = "SELECT NAME FROM users WHERE NAME = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results;
}

// Проверка занятости почты нового пользователя
function get_email(object $pdo, string $email)
{
    $query = "SELECT email FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results;
}

// Создание нового пользователя
function set_user(object $pdo, string $username, string $pwd, string $email){
    $query = "INSERT INTO users (NAME, pwd, email, avatar) VALUES (:username, :pwd, :email, :avatar);";
    $stmt = $pdo->prepare($query);

    $options = [
        'cost' => 12
    ];
    $avatar = "https://sun6-23.userapi.com/impg/WxtakHvcZ7x5F3kJ52BwPc0EqS-l9iobGs6eUQ/TOZTGxieV-w.jpg?size=1900x1900&quality=96&sign=9b5b80a56942e5239bc15d6337205eb1&type=album";
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":pwd", $hashedPwd);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":avatar", $avatar);

    $stmt->execute();
}






