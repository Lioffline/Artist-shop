<?php
// Подключения
require_once 'includes/config.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/main_view.php';
require_once 'css/styled.php';
require_once 'scripts/feed.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получение данных пользователя для предварительного заполнения формы
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение сообщений об ошибках из сессии
$error_message = isset($_SESSION["error_message"]) ? $_SESSION["error_message"] : '';
unset($_SESSION["error_message"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        <?php
        navbar_settings_setup();
        avatar_settings_setup(1);
        edit_settings_setup();
        ?>
    </style>
    <?php edit_script(); ?>
</head>
<body>
<!-- Навигационное поле -->
<?php nav_bar_show(); ?>
<progress class="progress is-small is-primary" value="100" max="100">30%</progress>

<!-- Страница настроек -->
<div class="container">
    <div class="columns">
        <div class="column is-one-quarter">
            <aside class="menu">
                <p class="menu-label">
                    Settings
                </p>
                <ul class="menu-list">
                    <li><a href="javascript:void(0)" onclick="showSection('profile-settings')">Profile Settings</a></li>
                    <li><a href="javascript:void(0)" onclick="showSection('account-settings')">Account Settings</a></li>
                </ul>
            </aside>
        </div>
        <div class="column">
            <?php if ($error_message): ?>
                <div class="notification is-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <div id="profile-settings" class="content-section">
                <h1 class="title">Profile Settings</h1>
                <form action="includes/edit_profile_process.php" method="post">
                    <div class="field">
                        <label class="label">Avatar</label>
                        <div class="control">
                            <div class="avatar-container" onclick="openAvatarModal()">
                                <img src="<?php echo htmlspecialchars($user["avatar"]); ?>" alt="Avatar" class="profile-avatar">
                                <div class="overlay">Click to change avatar</div>
                            </div>
                            <input class="input" type="text" name="avatar" id="avatar" placeholder="Enter your avatar URL" value="<?php echo htmlspecialchars($user["avatar"]); ?>" style="display: none;">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                            <input class="input" type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($user["NAME"]); ?>" required onkeyup="checkUsername()">
                        </div>
                        <p id="usernameFeedback" class="help"></p>
                    </div>
                    <div class="field">
                        <label class="label">Profile Description</label>
                        <div class="control">
                            <textarea class="textarea" name="description" id="description" placeholder="Enter your profile description"><?php echo htmlspecialchars($user["user_description"]); ?></textarea>
                        </div>
                    </div>
                    <div class="control">
                        <button type="submit" class="button is-link">Save</button>
                    </div>
                </form>
            </div>

            <div id="account-settings" class="content-section hidden">
                <h1 class="title">Account Settings</h1>
                <form action="includes/edit_profile_process.php" method="post">
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" type="email" name="email" id="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($user["email"]); ?>" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Current Password</label>
                        <div class="control">
                            <input class="input" type="password" name="current_password" id="current-password" placeholder="Enter your current password" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">New Password</label>
                        <div class="control">
                            <input class="input" type="password" name="new_password" id="new-password" placeholder="Enter your new password">
                        </div>
                    </div>
                    <div class="control">
                        <button type="submit" class="button is-link">Save</button>
                    </div>
                </form>
                <div class="field">
                <label class="label"></label>
                <div class="control">
                    <a href="includes/delete_profile.php?user_id=<?php echo htmlspecialchars($user["id"]);?>" class="button is-danger is-outline">Delete My Account</a>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для изменения аватара -->
<div class="modal" id="changeAvatarModal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Change Avatar</p>
      <button class="delete" aria-label="close" onclick="document.getElementById('changeAvatarModal').classList.remove('is-active')"></button>
    </header>
    <section class="modal-card-body">
      <p>Введите новый URL аватара:</p>
      <input class="input" type="text" id="newAvatarURL" placeholder="Enter your new avatar URL">
    </section>
    <footer class="modal-card-foot">
      <button class="button is-primary" onclick="updateAvatar()">Update Avatar</button>
      <button class="button" onclick="document.getElementById('changeAvatarModal').classList.remove('is-active')">Cancel</button>
    </footer>
  </div>
</div>

</body>
</html>
