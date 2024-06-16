<?php
// Подключения
require_once 'includes/config.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';
require_once 'scripts/feed.php';

// Получение идентификатора пользователя из параметра URL
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION["user_id"];

// Функция для получения количества подписчиков
function getSubscriberCount($pdo, $user_id) {
    $query = "SELECT COUNT(*) as count FROM subscriptions WHERE subscribed_user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

// Функция для получения списка подписчиков
function getSubscribers($pdo, $user_id) {
    $query = "SELECT users.id, users.NAME, users.email, users.avatar FROM subscriptions 
              JOIN users ON subscriptions.subscriber_id = users.id 
              WHERE subscriptions.subscribed_user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Получение информации о пользователе
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение постов пользователя
$post_query = "SELECT posts.*, users.avatar 
               FROM posts 
               JOIN users ON posts.creator = users.id 
               WHERE users.id = :user_id
               ORDER BY RAND();";

$post_stmt = $pdo->prepare($post_query);
$post_stmt->bindParam(":user_id", $user_id);
$post_stmt->execute();
$posts = $post_stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение количества подписчиков
$subscriber_count = getSubscriberCount($pdo, $user_id);

// Проверка подписан ли текущий пользователь на данный профиль
$is_subscribed = false;
if (isset($_SESSION["user_id"])) {
    $check_subscription_query = "SELECT COUNT(*) as count FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_user_id = :subscribed_user_id";
    $check_stmt = $pdo->prepare($check_subscription_query);
    $check_stmt->bindParam(":subscriber_id", $_SESSION["user_id"]);
    $check_stmt->bindParam(":subscribed_user_id", $user_id);
    $check_stmt->execute();
    $is_subscribed = $check_stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
}

$pdo = null;
$stmt = null;
$post_stmt = null;
$check_stmt = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="css/main.css" rel='stylesheet'>
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/1ov5nFfXq5lfhTbT6RcYzOgCwqUeJw/8U600k" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    <?php profile_settings_setup();
    navbar_settings_setup();
    avatar_settings_setup(1);?>
    <?php scrollbar_settings_setup(); ?>
</style>
    <script src="https://unpkg.com/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>
</head>
<body>
<!-- Навигационное поле -->
<?php nav_bar_show(); ?>
<progress class="progress is-small is-primary" value="100" max="100">30%</progress>
<!-- Страница профиля -->
<div class="container">
    <!-- Иконка профиля -->
    <div class="avatar-container">
        <img src="<?php echo htmlspecialchars($user["avatar"]);?>" alt="Avatar" class="profile-avatar">
    </div>
    <div class="content has-text-centered">
        <!-- Имя пользователя -->
        <p class="title is-4"><?php echo htmlspecialchars($user["NAME"]); ?></p>
        <!-- Email пользователя -->
        <p class="subtitle is-6"><?php echo htmlspecialchars($user["email"]); ?></p>
        <!-- Описание профиля -->
        <div class="content">
            <?php echo htmlspecialchars($user["user_description"]); ?>
            <br>
            <!-- Дата создания аккаунта -->
            <small>Joined on <?php echo date("F j, Y", strtotime($user["created_at"])); ?></small>
        </div>
        <div>
            <!-- Подписчики -->
            <a href="#" class="button is-link" id="subscriber-count"><?php echo $subscriber_count; ?> Followers</a>
        </div>
    </div>
    <footer class="card-footer">
        <?php
        if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $user_id): 
        ?>
            <a href="edit_profile.php" class="card-footer-item"><i class="fas fa-edit"></i> Edit</a>
            <a href="manage_balance.php?receiver_id=<?php echo $user_id; ?>" class="card-footer-item"><i class="fas fa-edit"></i>Balance</a>
        <?php
        elseif (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != $user_id): 
        ?>
            <a href="#" class="card-footer-item sub-button" id="subscription-button"><i class="fas fa-star"></i> <?php echo $is_subscribed ? 'Unsub' : 'Sub'; ?></a>
            <a href="#" class="card-footer-item message-button"><i class="fas fa-envelope"></i> Message</a>
            <a href="wallet.php?receiver_id=<?php echo $user_id; ?>" class="card-footer-item"><i class="fas fa-edit"></i>Wallet</a>
        <?php else: ?>
            <a href="login.php" class="card-footer-item" id="fake subscription-button"><i class=" fake fas fa-star"></i>Sub</a>
            <a href="login.php" class="card-footer-item"><i class="fake fas fa-envelope"></i> Message</a>
            <a href="login.php" class="card-footer-item"><i class="fake fas fa-edit"></i>Wallet</a>
        <?php endif; ?>
        <?php
            if (isset($_SESSION["user_id"]) &&  $_SESSION['is_admin']): 
        ?>
            <a href="includes/delete_profile.php?user_id=<?php echo $user_id;?>" class="card-footer-item"><i class="fas fa-trash-alt"></i> Ban</a>
        <?php endif;?>
    </footer>
</div>
 <!-- Лента -->
<div class="container cards-container" id="masonry-grid">
    <?php
    if (empty($posts)) {
        echo "<div class='notification is-danger'>";
        echo "<p>No posts</p>";
        echo "</div>";
    } else {
        foreach ($posts as $row) {
            echo '<div class="card">';
            echo '  <a href="post.php?id=' . htmlspecialchars($row["id"]) . '">';
            echo '    <img src="' . htmlspecialchars($row["image_link"]) . '" alt="Image">';
            echo '  </a>';
            echo '  <div class="info">';
            echo '    <div class="avatar">';
            echo '      <img src="' . htmlspecialchars($row["avatar"]) . '" alt="Avatar">';
            echo '      <span>' . htmlspecialchars($user["NAME"]) . '</span>';
            echo '    </div>';
            echo '    <div class="title">' . htmlspecialchars($row["title"]) . '</div>';
            echo '  </div>';
            echo '</div>';
        }
    }
    ?>
</div>
<?php feed_position(); ?>
<!-- Модальное окно для подписчиков -->
<div class="modal" id="subscribersModal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Followers</p>
            <button class="delete" aria-label="close" onclick="closeModal()"></button>
        </header>
        <section class="modal-card-body">
            <ul id="subscribers-list">
                <!-- Список подписчиков загружается через JavaScript -->
            </ul>
        </section>
    </div>
</div>
<script>
    document.getElementById('subscriber-count').addEventListener('click', function() {
        fetchSubscribers();
    });

    document.getElementById('subscription-button').addEventListener('click', function() {
        toggleSubscription();
    });

    function fetchSubscribers() {
    fetch('includes/get_subscribers.php?user_id=<?php echo $user_id;?>')
      .then(response => response.json())
      .then(data => {
            const list = document.getElementById('subscribers-list');
            list.innerHTML = ''; 
            data.forEach(subscriber => {
                const listItem = document.createElement('li');
                listItem.classList.add('subscriber-info');
                listItem.innerHTML = `
                    <a href="profile.php?user_id=${subscriber.id}" class="box is-shadowless">
                    <div class="post-container">
                        <div class="post-header">
                                <img src="${subscriber.avatar}" alt="Avatar">
                            <div>
                                <p class="post-title">${subscriber.NAME}</p>
                                <p class="post-subtitle">${subscriber.email}</p>
                            </div>
                        </div>
                    </div>
                    </a>
                `;
                list.appendChild(listItem);
            });
            document.getElementById('subscribersModal').classList.add('is-active');
        });
    }

    function closeModal() {
        document.getElementById('subscribersModal').classList.remove('is-active');
    }

    function toggleSubscription() {
        fetch('includes/toggle_subscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                subscriber_id: <?php echo $_SESSION["user_id"]; ?>,
                subscribed_user_id: <?php echo $user_id; ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            const button = document.getElementById('subscription-button');
            button.innerHTML = `<i class="fas fa-star"></i> ${data.subscribed ? 'Unsub' : 'Sub'}`;
            document.getElementById('subscriber-count').innerText = `${data.subscriber_count} Followers`;
        });
    }
    document.querySelector('.message-button').addEventListener('click', function() {
    startConversation();
    });

    function startConversation() {
        fetch('includes/start_conversation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sent_by: <?php echo $_SESSION["user_id"]; ?>,
                get_by: <?php echo $user_id; ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `chats.php`;
            } else {
                alert('Error starting conversation');
            }
        });
}
</script>
</body>
</html>