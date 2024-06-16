<?php
require_once 'config.php';
require_once 'dbh.inc.php';

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM users WHERE NAME = :name AND id != :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();

    $response = ["exists" => $stmt->rowCount() > 0];
    echo json_encode($response);
}
?>
