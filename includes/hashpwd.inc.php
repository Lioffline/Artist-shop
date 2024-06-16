<?php

$pwdSignup = "Krossing";
$options = [
'cost' => 12
];

$hashedPwd = password_hash($pwdSignup, PASSWORD_BCRYPT, $options);

$pwdLogin = "Krossi3ng";

if (password_verify($pwdLogin, $hashedPwd)) {
    echo "They are the same!";
} else {
    echo "They are not the same!";
}

/* Метод хэширования не парольных данных
$sensitiveData = "Krossing";
$salt = bin2hex(random_bytes(16));
$pepper = "ASecretPepperString";

$dataToHash = $sensitiveData . $salt . $pepper;
$hash = hash("sha256", $dataToHash);


$sensitiveData = "Krossing";

$storedSalt = $salt;
$storedHash = $hash;
$pepper = "ASecretPepperString";

$dataToHash = $sensitiveData . $storedSalt . $pepper;
$verificationHash = hash("sha256", $dataToHash);


if ($storedHash == $verificationHash){
    echo "data is the same!";
} else {
    echo "data is not the same";
}
*/
?>
