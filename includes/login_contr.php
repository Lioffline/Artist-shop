<?php

declare(strict_types = 1);

// Проверка пустых значений
function is_input_empty(string $username, string $pwd){
    if(empty($username) || empty($pwd)){
        return true;
    } else {
        return false;
    }
}

// Проверка неправильного имени
function is_username_wrong(bool|array $results)
{
    if(!$results){
        return true;
    } else {
        return false;
    }
}

// Проверка неправильного пароля
function is_password_wrong(string $pwd, string $hashedPwd)
{
    if(!password_verify($pwd, $hashedPwd)){
        return true;
    } else {
        return false;
    }
}