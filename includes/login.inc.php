<?php

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["NAME"];
    $pwd = $_POST["pwd"];

    try {
        require_once "dbh.inc.php";
        require_once "login_model.php";
        require_once "login_view.php";
        require_once "login_contr.php";

        // Обработка ошибок

        $errors = [];

        $results = get_user($pdo, $username);

        if (is_input_empty($username, $pwd)) {
            $errors["empty_input"] = "Fill every single field";
        }
        if (!is_username_wrong($results) && is_password_wrong($pwd, $results["pwd"])) {
            $errors["empty_input"] = "Incorrect password";
        }
        if(is_username_wrong($results)){
            $errors["login_incorrect"] = "Incorrect login info";
        }

        require_once 'config.php';

        if($errors){
            $_SESSION["errors_login"] = $errors;

            $_SESSION["signup_Data"] = $signupData;

            header("Location: ../login.php");
            die();
        }

        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $results["id"];
        session_id($sessionId);

        $_SESSION["user_id"] = $results["id"];
        $_SESSION["username"] = htmlspecialchars($results["NAME"]);
        $_SESSION["is_admin"] = $results["is_admin"];

        $_SESSION['last_regeneration'] = time();

        header("Location: ../index.php?login=success");

        $pdo  = null;
        $stmt = null;

        die();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    } 

} else {
    header("Location: ../index.php");
    die();
}

?>