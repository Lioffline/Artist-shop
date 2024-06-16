<?php
require_once 'includes/config.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';
require_once 'scripts/feed.php';
require_once 'scripts/moduls.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Получение чатов
try {
    require_once "includes/dbh.inc.php";
    $userId = $_SESSION['user_id'];

    $query = "SELECT c.*, u.avatar, u.name AS user_name, u.id AS user_id
              FROM Conversations c
              JOIN Messages m ON c.id = m.conversation_id
              JOIN users u ON (m.sent_by = u.id OR m.get_by = u.id) AND u.id != :user_id
              WHERE m.sent_by = :user_id OR m.get_by = :user_id
              GROUP BY c.id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmt->execute();
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching chats: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <link href="css/bulma.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/1ov5nFfXq5lfhTbT6RcYzOgCwqUeJw/8U600k" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        <?php search_settings_setup(); ?>
        <?php navbar_settings_setup(); ?>
        <?php chat_settings_setup(); ?>
        <?php scrollbar_settings_setup(); ?>
    </style>
</head>
<body>
    <?php nav_bar_show(); ?>
    <progress class="progress is-small is-primary" value="100" max="100">30%</progress>
    <div class="chat-container">
        <div class="chat-list">
            <?php foreach ($chats as $chat): ?>
                <div class="chat-item" data-chat-id="<?php echo $chat['id']; ?>">
                    <img src="<?php echo htmlspecialchars($chat['avatar']); ?>" alt="Avatar" width="40" height="40">
                    <div class="chat-info">
                        <p class="chat-name"><?php echo htmlspecialchars($chat['name']); ?></p>
                        <p class="chat-last-message"><?php echo htmlspecialchars($chat['user_name']); ?></p>
                    </div>
                    <div class="chat-actions">
                        <button class="button is-small is-light chat-options-btn" aria-haspopup="true" aria-controls="options-menu-<?php echo $chat['id']; ?>">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="options-menu-<?php echo $chat['id']; ?>" class="chat-actions-menu">
                            <div class="menu-item edit-chat-btn" data-chat-id="<?php echo $chat['id']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </div>
                            <div class="menu-item delete-chat-btn" data-chat-id="<?php echo $chat['id']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </div>

                        </div>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="chat-content">
            <!-- Сообщения -->
            <div id="messages" class="messages-container"></div>
            <div class="message-form">
                <div class="input-wrapper">
                    <!--<button class="button is-wallet" id="walletBtn">
                        <i class="fas fa-wallet"></i>
                    </button> -->
                    <textarea id="messageInput" class="textarea" placeholder="Type a message..."></textarea>
                    <button class="button is-primary is-send-message" id="sendMessageBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php edit_chat_name_module(); ?>
    <script>
    <?php chat_script(); ?>
    </script>
</body>
</html>