<?php
require_once 'config.php';
require_once 'dbh.inc.php';

$data = json_decode(file_get_contents('php://input'), true);
// Удаление чата
if (isset($data['chat_id'])) {
    $chatId = $data['chat_id'];

    $query = "DELETE FROM Conversations WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $chatId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
