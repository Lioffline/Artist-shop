<?php

// Обновление пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["NAME"];
    $pwd = $_POST["pwd"];
    $email = $_POST["email"];
    $u_descripion = $_POST["user_description"];
    $avatar = $_POST["avatar"];
    $u_id_upd = $_POST["id"];

    try {
        require_once "dbh.inc.php";
        $query = "UPDATE users SET NAME = :username, pwd = :pwd, email = :email, user_description = :user_description, 
        avatar = :avatar  WHERE id = :id";

        $stmt = $pdo->prepare($query);

        $options = [
            'cost' => 12
        ];
            
        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":pwd", $hashedPwd);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":user_description", $user_description);
        $stmt->bindParam(":id", $u_id_upd);

        $stmt->execute();

        $pdo  = null;
        $stmt = null;

        header("Location: ../index.php");

        die();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    } 
}   else {
    header("Location: ../index.php");
}
?>