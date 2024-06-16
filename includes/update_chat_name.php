<?php
require_once 'config.php';
require_once 'dbh.inc.php';

$data = json_decode(file_get_contents('php://input'), true);
// Обновление имени беседы
if (isset($data['chat_id']) && isset($data['chat_name'])) {
    $chatId = $data['chat_id'];
    $chatName = $data['chat_name'];

    $query = "UPDATE Conversations SET name = :name WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":name", $chatName, PDO::PARAM_STR);
    $stmt->bindParam(":id", $chatId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
