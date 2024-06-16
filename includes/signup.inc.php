<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["NAME"];
    $pwd = $_POST["pwd"];
    $email = $_POST["email"];
    try {
        require_once "dbh.inc.php";
        require_once "signup_model.php";
        require_once "signup_view.php";
        require_once "signup_contr.php";

        // Обработка ошибок

        $errors = [];

        if (is_input_empty($username, $pwd, $email)) {
            $errors["empty_input"] = "Fill every single field";
        }
        if (is_email_invalid($email)) {
            $errors["invalid_email"] = "Invalid email used";
        }
        if (is_username_taken($pdo, $username)) {
            $errors["username_taken"] = "Username already taken";
        }
        if (is_email_registrated($pdo, $email)) {
            $errors["email_used"] = "Email is already registered";
        }

        require_once 'config.php';

        if($errors){
            $_SESSION["errors_signup"] = $errors;

            $signupData = [
                "username" => $username,
                "email" => $email
            ];

            $_SESSION["signup_Data"] = $signupData;

            header("Location: ../signup.php");
            die();
        }
        
        set_user($pdo, $username, $pwd, $email);
 
        header("Location: ../index.php?signup=success");

        $pdo  = null;
        $stmt = null;

        die();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    } 
} else {
    header("Location: ../index.php");
}
?>