<?php
require_once 'config.php';
require_once 'dbh.inc.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$current_user_id = $_SESSION['user_id'];

// Получение ленты подписчиков
if ($user_id) {
    try {
        $query = "SELECT users.id, users.NAME, users.email, users.avatar, 
                  EXISTS(SELECT 1 FROM subscriptions WHERE subscriber_id = :current_user_id AND subscribed_user_id = users.id) as is_subscribed 
                  FROM subscriptions 
                  JOIN users ON subscriptions.subscriber_id = users.id 
                  WHERE subscriptions.subscribed_user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":current_user_id", $current_user_id);
        $stmt->execute();
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($subscribers);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid user ID']);
}

$pdo = null;
$stmt = null;
?>
