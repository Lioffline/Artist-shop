<?php
declare(strict_types = 1);

function signup_input() {
    // Проверка наличия данных пользователя и ошибок регистрации
    $username = isset($_SESSION["signup_Data"]["username"])? $_SESSION["signup_Data"]["username"] : '';
    $email = isset($_SESSION["signup_Data"]["email"])? $_SESSION["signup_Data"]["email"] : '';

    // Определение значений для полей ввода на основе данных пользователя и ошибок
    $usernameValue = isset($_SESSION["errors_signup"]["username_taken"]) || empty($username)? '' : $username;
    $emailValue = isset($_SESSION["errors_signup"]["email_used"]) || isset($_SESSION["errors_signup"]["invalid_email"]) || empty($email)? '' : $email;

    // Вывод полей ввода с использованием новых классов Bulma и иконок Font Awesome
    echo '
        <div class="field">
            <label class="label">Nickname</label>
            <div class="control has-icons-left">
                <input class="input" type="text" name="NAME" placeholder="Enter nickname" value="'. $usernameValue. '" required>
                <span class="icon is-small is-left">
                    <i class="fa fa-user"></i>
                </span>
            </div>
        </div>
        <div class="field">
            <label class="label">Password</label>
            <div class="control has-icons-left">
                <input class="input" type="password" name="pwd" placeholder="Enter password" required>
                <span class="icon is-small is-left">
                    <i class="fa fa-lock"></i>
                </span>
            </div>
        </div>
        <div class="field">
            <label class="label">Email</label>
            <div class="control has-icons-left">
                <input class="input" type="email" name="email" placeholder="Enter email" value="'. $emailValue. '" required>
                <span class="icon is-small is-left">
                    <i class="fa fa-envelope"></i>
                </span>
            </div>
        </div>';
}

// Проверка на наличие ошибок
function check_signup_errors() {
    if (isset($_SESSION['errors_signup'])) {
        $errors = $_SESSION['errors_signup'];
        foreach ($errors as $error) {
            echo '<p class="form-error">' . $error . '</p>';
        }
        unset($_SESSION['errors_signup']);
    } else if (isset($_GET["signup"]) && $_GET["signup"] === "success") {
        echo '<p class="form-success">Signup success!</p>';
    }
}

// Настройка формы входа
function signup_settings(){
    ?>
     .center-form {
            height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
       .column.is-6 {
            width: 80%;
            max-width: 400px;
        }
        @media (max-width: 768px) {
           .column.is-6 {
                width: 90%;
                max-width: 350px; 
            }
        }
        .section {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .container.has-text-centered {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-error {
            color: red;
            margin: 0.5em 0;
        }
        .form-success {
            color: green;
            margin: 0.5em 0;
        }
    <?php
}





