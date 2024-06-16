<?php
require_once 'dbh.inc.php';
require_once 'config.php';

if (!isset($_GET['chat_id']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$chatId = $_GET['chat_id'];
$userId = $_SESSION['user_id'];

// Получение сообщений из беседы
try {
    $query = "SELECT * FROM Messages WHERE conversation_id = :chat_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":chat_id", $chatId, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'messages' => $messages]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
