<?php

// Удаление пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["NAME"];
    $pwd = $_POST["pwd"];
    try {
        require_once "dbh.inc.php";
        $query = "SELECT * FROM users WHERE NAME = :username;";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($pwd, $user['pwd'])) { 
                $deleteQuery = "DELETE FROM users WHERE NAME = :username;";
                
                $deleteStmt = $pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(":username", $username);
                $deleteStmt->execute();
                
                $pdo = null;
                $stmt = null;
                $deleteStmt = null;
                
                header("Location:../index.php");
                die();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    } 
} else {
    header("Location:../index.php");
}
?>
