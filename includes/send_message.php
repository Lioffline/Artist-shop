<?php

require_once 'dbh.inc.php';
require_once 'config.php';

$requestData = json_decode(file_get_contents('php://input'));

// Полученные данных из JSON
$chatId = $requestData->chat_id;
$messageContent = $requestData->message_content;
$userId = $_SESSION['user_id'];

// Передача таблицы в базу данных
$query = "INSERT INTO Messages (content, sent_by, get_by, conversation_id)
          VALUES (:content, :sent_by, :get_by, :conversation_id)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':content', $messageContent, PDO::PARAM_STR);
$stmt->bindParam(':sent_by', $userId, PDO::PARAM_INT);
$stmt->bindParam(':get_by', $userId, PDO::PARAM_INT);
$stmt->bindParam(':conversation_id', $chatId, PDO::PARAM_INT);

if ($stmt->execute()) {
    $response = ['success' => true];
} else {
    $response = ['success' => false];
}

header('Content-Type: application/json');
echo json_encode($response);