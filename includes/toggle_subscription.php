<?php
require_once 'config.php';
require_once 'dbh.inc.php';

$data = json_decode(file_get_contents('php://input'), true);

$subscriber_id = isset($data['subscriber_id']) ? $data['subscriber_id'] : null;
$subscribed_user_id = isset($data['subscribed_user_id']) ? $data['subscribed_user_id'] : null;

if ($subscriber_id && $subscribed_user_id) {
    try {
        // Проверка существующей подписки
        $check_query = "SELECT COUNT(*) as count FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_user_id = :subscribed_user_id";
        $check_stmt = $pdo->prepare($check_query);
        $check_stmt->bindParam(":subscriber_id", $subscriber_id);
        $check_stmt->bindParam(":subscribed_user_id", $subscribed_user_id);
        $check_stmt->execute();
        $is_subscribed = $check_stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;

        if ($is_subscribed) {
            // Удаление подписки
            $query = "DELETE FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_user_id = :subscribed_user_id";
        } else {
            // Добавление подписки
            $query = "INSERT INTO subscriptions (subscriber_id, subscribed_user_id) VALUES (:subscriber_id, :subscribed_user_id)";
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":subscriber_id", $subscriber_id);
        $stmt->bindParam(":subscribed_user_id", $subscribed_user_id);
        $stmt->execute();

        // Получение обновленного количества подписчиков
        $subscriber_count_query = "SELECT COUNT(*) as count FROM subscriptions WHERE subscribed_user_id = :subscribed_user_id";
        $subscriber_count_stmt = $pdo->prepare($subscriber_count_query);
        $subscriber_count_stmt->bindParam(":subscribed_user_id", $subscribed_user_id);
        $subscriber_count_stmt->execute();
        $subscriber_count = $subscriber_count_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        echo json_encode(['subscribed' => !$is_subscribed, 'subscriber_count' => $subscriber_count]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid input']);
}

$pdo = null;
$check_stmt = null;
$stmt = null;
$subscriber_count_stmt = null;
?>
