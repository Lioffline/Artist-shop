<?php
// Подключения
require_once 'includes/config.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';
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
        <?php signup_settings(); ?>
        <?php navbar_settings_setup();  ?>
        <?php scrollbar_settings_setup(); ?>
        .icon-user {
        margin-right: 5px;
        }
    </style>
</head>
<body>
<!-- Навигационное поле -->
<?php nav_bar_show(); ?>
<progress class="progress is-small is-primary" value="100" max="100">30%</progress>
<section class="section"><div class="container has-text-centered"><h1 class="title"><strong>SIGN-UP</strong></h1></div></section>

<div class="center-form">
    <div class="column is-6">
        <form action="includes/signup.inc.php" method="POST" class="box">
            <?php signup_input(); ?>
            <?php check_signup_errors(); ?>
            <button class="button is-primary">Sign-up</button>
        </form>
    </div>
</div>       

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
