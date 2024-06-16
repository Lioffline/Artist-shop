<?php
declare(strict_types = 1);

// Вывод имени для дебага
function output_username(){
    if (isset($_SESSION["user_id"])){
        echo "You are logged in as " . $_SESSION["username"]; 
    }else {
        echo "You are not logged in";
    }

}

// Поля ввода
function login_input() {
    echo '
    
        <div class="field">
            <label class="label">Nickname</label>
            <div class="control has-icons-left">
                <input class="input" type="text" name="NAME" placeholder="Enter nickname" required>
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
        </div>';
}

// Проверка ошибок входа
function check_login_errors() {
    if (isset($_SESSION['errors_login'])) {
        $errors = $_SESSION['errors_login'];
        foreach ($errors as $error) {
            echo '<p class="form-error">' . $error . '</p>';
        }
        unset($_SESSION['errors_login']);
    } else if (isset($_GET["login"]) && $_GET["login"] === "success") {
        echo '<p class="form-success">Signup success!</p>';
    }
}
