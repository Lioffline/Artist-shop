<?php
require_once 'config.php';
require_once 'dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sent_by = $data['sent_by'];
    $get_by = $data['get_by'];

    try {
        // Начало транзакции
        $pdo->beginTransaction();

        // Создание новой беседы
        $conversation_query = "INSERT INTO Conversations (name) VALUES ('Беседа')";
        $pdo->exec($conversation_query);
        $conversation_id = $pdo->lastInsertId();

        // Создание первого сообщения в беседе
        $message_query = "INSERT INTO Messages (content, sent_by, get_by, conversation_id) VALUES ('Start of the conversation', :sent_by, :get_by, :conversation_id)";
        $stmt = $pdo->prepare($message_query);
        $stmt->bindParam(':sent_by', $sent_by);
        $stmt->bindParam(':get_by', $get_by);
        $stmt->bindParam(':conversation_id', $conversation_id);
        $stmt->execute();

        // Завершение транзакции
        $pdo->commit();

        echo json_encode(['success' => true, 'conversation_id' => $conversation_id]);
    } catch (Exception $e) {
        // Откат транзакции в случае ошибки
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
