<?php
require_once 'dbh.inc.php';
session_start();

// Создание репорта
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporterId = $_POST['reporter_id'];
    $reportedPostId = $_POST['reported_post_id'];
    $reason = $_POST['reason'];
    $description = $_POST['description'];

    if (empty($reporterId) || empty($reportedPostId) || empty($reason) || empty($description)) {
        die("All fields are required.");
    }

    try {
        $query = "INSERT INTO reports (reporter_id, reported_post_id, reason, description) VALUES (:reporter_id, :reported_post_id, :reason, :description)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':reporter_id', $reporterId);
        $stmt->bindParam(':reported_post_id', $reportedPostId);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        $pdo = null;
        $stmt = null;

        header("Location: ../post.php?id=$reportedPostId&report=success");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
?>
