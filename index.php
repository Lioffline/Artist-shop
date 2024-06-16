<?php
// Подключения
require_once 'includes/config.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';
require_once 'scripts/feed.php';

// Получение постов для главной страницы
try {
    require_once "includes/dbh.inc.php";
    $query = "SELECT posts.*, users.avatar, users.NAME 
    FROM posts 
    JOIN users ON posts.creator = users.id 
    ORDER BY RAND();";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo  = null;
    $stmt = null;

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/1ov5nFfXq5lfhTbT6RcYzOgCwqUeJw/8U600k" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        <?php index_settings_setup(); ?>
        <?php navbar_settings_setup();  ?>
        <?php scrollbar_settings_setup(); ?>
        .card:not(.is-masonry-loaded) {
            visibility: hidden;
        }
    </style>
    <script src="https://unpkg.com/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>
</head>
<body>
<!-- Навигационное поле -->
<?php nav_bar_show(); ?>
<progress class="progress is-small is-primary" value="100" max="100">30%</progress>

    <!-- Лента постов -->
<div class="container cards-container" id="masonry-grid">
    <?php
    if (empty($results)) {
        echo "<div class='notification is-danger'>";
        echo "<p>No posts</p>";
        echo "</div>";
    } else {
        foreach ($results as $row) {
            echo '<div class="card is-masonry-loaded">';
            echo '  <a href="post.php?id=' . htmlspecialchars($row["id"]) . '">';
            echo '    <img src="' . htmlspecialchars($row["image_link"]) . '" alt="Image">';
            echo '  </a>';
            echo '  <div class="info">';
            echo '    <div class="avatar">';
            echo '      <img src="' . htmlspecialchars($row["avatar"]) . '" alt="Avatar">';
            echo '      <span>' . htmlspecialchars($row["NAME"]) . '</span>';
            echo '    </div>';
            echo '    <div class="title">' . htmlspecialchars($row["title"]) . '</div>';
            echo '  </div>';
            echo '</div>';
        }
    }
    ?>
</div>
    <!-- Скрипты -->
<?php feed_position(); ?>
<?php open_bar_script(); ?>
</body>
</html>
