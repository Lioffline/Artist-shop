<?php
declare(strict_types=1);
// Подключение
require_once 'includes/config.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/main_view.php';
require_once 'css/styled.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$postTitle = $postImageURL = $postTags = $postDescription = '';
$errors = [];

// Создание поста
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['post_title'], $_POST['post_image_url'])) {
        $postTitle = $_POST['post_title'];
        $postImageURL = $_POST['post_image_url'];

        if (empty($postImageURL)) {
            $errors[] = "Please upload an image.";
        }

        $postDescription = $_POST['post_description'] ?? '';
        $postTags = $_POST['post_tags'] ?? '';

        if (empty($errors)) {
            $query = "INSERT INTO posts (title, image_link, short_desc, tags, creator, post_data) 
                      VALUES (:title, :image_link, :short_desc, :tags, :creator, NOW())";
            $stmt = $pdo->prepare($query);

            $creator = $_SESSION['user_id'];
            $stmt->bindParam(':title', $postTitle);
            $stmt->bindParam(':image_link', $postImageURL);
            $stmt->bindParam(':short_desc', $postDescription);
            $stmt->bindParam(':tags', $postTags);
            $stmt->bindParam(':creator', $creator);

            $stmt->execute();

            header("Location: index.php");
            exit();
        }
    } else {
        $errors[] = "Please fill in all required fields.";
    }
}

$error_message = !empty($errors) ? implode('<br>', $errors) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        <?php
        navbar_settings_setup();
        createpost_settings_setup();  ?>
        <?php scrollbar_settings_setup(); ?>

    </style>
    <script>
        function openAvatarModal() {
            document.getElementById('changeAvatarModal').classList.add('is-active');
        }

        function updateAvatar() {
            const newAvatarUrl = document.getElementById('newAvatarURL').value;
            const avatarInput = document.getElementById('avatar');
            avatarInput.value = newAvatarUrl;
            const avatarImageContainer = document.querySelector('.avatar-container');

            const newImage = new Image();
            newImage.src = newAvatarUrl;
            newImage.onload = function() {
                const aspectRatio = newImage.width / newImage.height;
                const maxWidth = 500;
                const maxHeight = 650;

                let width = newImage.width;
                let height = newImage.height;

                if (width > maxWidth) {
                    width = maxWidth;
                    height = width / aspectRatio;
                }
                if (height > maxHeight) {
                    height = maxHeight;
                    width = height * aspectRatio;
                }

                avatarImageContainer.style.width = width + 'px';
                avatarImageContainer.style.height = height + 'px';

                const existingImage = avatarImageContainer.querySelector('img');
                if (existingImage) {
                    existingImage.src = newAvatarUrl;
                } else {
                    const placeholder = avatarImageContainer.querySelector('.placeholder');
                    if (placeholder) {
                        placeholder.outerHTML = `<img src="${newAvatarUrl}" alt="Post Image" class="profile-avatar">`;
                    }
                }
                document.getElementById('avatar').value = newAvatarUrl;
            };
            document.getElementById('changeAvatarModal').classList.remove('is-active');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const updateImageButton = document.querySelector('.update-button');
            if (updateImageButton) {
                updateImageButton.addEventListener('click', updateAvatar);
            }
        });
    </script>
</head>
<body>
    <!-- Навигационное поле -->
    <?php nav_bar_show(); ?>
    <progress class="progress is-small is-primary" value="100" max="100">30%</progress>

    <div class="container">
        <div class="columns">
            <div class="column">
                <?php if ($error_message): ?>
                    <div class="notification is-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <div class="post-container">
                    <div class="post-image-container">
                        <div class="avatar-container" onclick="openAvatarModal()">
                            <?php if (!empty($postImageURL)): ?>
                                <img src="<?php echo htmlspecialchars($postImageURL); ?>" alt="Post Image" class="profile-avatar" id="avatar-image">
                            <?php else: ?>
                                <div class="placeholder"><i class="fas fa-save"></i></div>
                            <?php endif; ?>
                            <div class="overlay"><i class="fas fa-upload"></i></div>
                        </div>
                    </div>
                    <div class="post-details">
                        <form action="create_post.php" method="post">
                            <div class="field">
                                <label class="label">Post Title</label>
                                <div class="control">
                                    <input class="input" type="text" name="post_title" id="post_title" placeholder="Enter post title" value="<?php echo htmlspecialchars($postTitle); ?>" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Post Description</label>
                                <div class="control">
                                    <textarea class="textarea" name="post_description" id="post_description" placeholder="Enter post description"><?php echo htmlspecialchars($postDescription); ?></textarea>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Post Tags</label>
                                <div class="control">
                                    <input class="input" type="text" name="post_tags" id="post_tags" placeholder="Enter tags" value="<?php echo htmlspecialchars($postTags); ?>">
                                </div>
                            </div>
                            <input type="hidden" name="post_image_url" id="avatar" value="<?php echo htmlspecialchars($postImageURL); ?>">
                            <div class="control">
                                <button type="submit" class="button is-link">Create Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Обновление фотографии поста (Да, использовался модуль аватара за основу) -->
    <div class="modal" id="changeAvatarModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Change Image</p>
                <button class="delete" aria-label="close" onclick="document.getElementById('changeAvatarModal').classList.remove('is-active')"></button>
            </header>
            <section class="modal-card-body">
                <p>Enter new image URL:</p>
                <input class="input" type="text" id="newAvatarURL" placeholder="Enter your new image URL">
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary update-button">Update Image</button>
                <button class="button" onclick="document.getElementById('changeAvatarModal').classList.remove('is-active')">Cancel</button>
            </footer>
        </div>
    </div>
</body>
</html>
