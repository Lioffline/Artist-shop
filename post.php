<?php
require_once 'includes/config.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';

// Получение данных о посте
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    try {
        require_once "includes/dbh.inc.php";
        $query = "SELECT posts.*, users.*
          FROM posts 
          JOIN users ON posts.creator = users.id 
          WHERE posts.id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $postId);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;

        if (!$post) {
            header("Location: index.php");
            die("Post not found.");
        }

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/1ov5nFfXq5lfhTbT6RcYzOgCwqUeJw/8U600k" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        <?php search_settings_setup(); ?>
        <?php navbar_settings_setup(); ?> 
        <?php post_settings_setup(); ?>
        <?php scrollbar_settings_setup();?>
        .modal {
            display: none;
        }
        .modal.is-active {
            display: flex;
        }
    </style>
</head>
<body>
    <!-- Навигационное поле -->
    <?php nav_bar_show(); ?>
    <progress class="progress is-small is-primary" value="100" max="100">30%</progress>
    <div class="post-container">
        <div class="post-header">
            <a href="profile.php?user_id=<?php echo $post['id']; ?>">
                <img  class="avatar-container" src="<?php echo htmlspecialchars($post['avatar']); ?>" alt="Avatar">
            </a>
            <div>
                <p class="post-title"><?php echo htmlspecialchars($post['title']); ?></p>
                <p class="post-subtitle"><?php echo htmlspecialchars($post['NAME']); ?></p>
            </div>
        </div>
        <div class="post-content">
            <figure class="image">
                <img src="<?php echo htmlspecialchars($post['image_link']); ?>" alt="Post Image">
            </figure>
            <div>
                <p><?php echo htmlspecialchars($post['short_desc']); ?></p>
                <div class="post-tags">
                    <?php
                        $tags = explode(',', $post['tags']);
                        foreach ($tags as $tag) {
                            echo '<span class="tag">' . htmlspecialchars(trim($tag)) . '</span>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="post-meta">
            <p>Posted by <?php echo htmlspecialchars($post['NAME']); ?></p>
            <div class="post-options">
                <i class="fas fa-ellipsis-v options-icon"></i>
                <div class="options-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="report-post-btn button is-light is-small">Report Post</button>
                    <?php if ($_SESSION['user_id'] == $post['creator'] || $_SESSION['is_admin']): ?>
                        <form id="delete-post-form" action="includes/delete_post.php" method="POST">
                            <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                            <button type="submit" class="delete-post-btn button is-danger is-small">Delete Post</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="post-meta">
            <p><?php echo htmlspecialchars($post['post_data']); ?></p>
        </div>
    </div>

    <!-- Модальное окно для репорта поста -->
    <div class="modal" id="reportModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Report Post</p>
                <button class="delete" aria-label="close" onclick="closeModal()"></button>
            </header>
            <section class="modal-card-body">
                <form id="report-post-form" action="includes/report_post.php" method="POST">
                    <div class="field">
                        <label class="label">Reason</label>
                        <div class="control">
                            <div class="select">
                                <select name="reason" required>
                                    <option value="Spam">Spam</option>
                                    <option value="Harassment">Harassment</option>
                                    <option value="Inappropriate Content">Inappropriate Content</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Description</label>
                        <div class="control">
                            <textarea class="textarea" name="description" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="reported_post_id" value="<?php echo $postId; ?>">
                    <input type="hidden" name="reporter_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <button type="submit" class="button is-danger">Submit Report</button>
                </form>
            </section>
        </div>
    </div>

    <!-- JavaScript для интерактивности меню -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha384-XeT62dehEzv9wDmrfjQtGw/W9sPTKMFdGqj7n/XASzYYq4NtL1ivGmY+YRddODSj" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const optionsIcon = document.querySelector('.options-icon');
            const optionsMenu = document.querySelector('.options-menu');
            const reportPostBtn = document.querySelector('.report-post-btn');
            const reportModal = document.getElementById('reportModal');
            const deletePostForm = document.getElementById('delete-post-form');

            optionsIcon.addEventListener('click', function() {
                optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!optionsIcon.contains(e.target) && !optionsMenu.contains(e.target)) {
                    optionsMenu.style.display = 'none';
                }
            });

            reportPostBtn.addEventListener('click', function() {
                reportModal.classList.add('is-active');
            });

            function closeModal() {
                reportModal.classList.remove('is-active');
            }

            document.querySelector('.modal .delete').addEventListener('click', closeModal);
            document.querySelector('.modal-background').addEventListener('click', closeModal);

            if (deletePostForm) {
                deletePostForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm("Are you sure you want to delete this post?")) {
                        this.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>
