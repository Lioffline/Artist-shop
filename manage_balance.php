<?php
// Подключение конфигурации и базы данных
require_once 'includes/config.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';
require_once 'scripts/feed.php';

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение текущего баланса пользователя
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Обработка случая, если пользователь не найден (не должно происходить в нормальном потоке)
    header("Location: profile.php");
    exit();
}

// Обработка отправки формы для пополнения или снятия баланса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка, что все поля формы заполнены
    if (!isset($_POST['card_number']) || !isset($_POST['password']) || !isset($_POST['expiration_date']) || !isset($_POST['amount'])) {
        $error = "Please fill in all fields of the form";
    } elseif (empty($_POST['card_number']) || empty($_POST['password']) || empty($_POST['expiration_date']) || empty($_POST['amount'])) {
        $error = "Please fill in all fields of the form";
    } else {
        // Удаление пробелов из номера карты
        $card_number = str_replace(' ', '', $_POST['card_number']);

        // Валидация суммы (должна быть положительным числом)
        $amount = $_POST['amount'];
        if (!is_numeric($amount) || $amount <= 0) {
            $error = "Incorrect amount";
        } else {
            // Валидация номера карты (проверка на 16 цифр)
            if (!preg_match('/^\d{16}$/', $card_number)) {
                $error = "The card number format is incorrect";
            }

            // Валидация срока действия (проверка на MM/YY)
            $expiration_date = $_POST['expiration_date'];
            if (!preg_match('/^\d{2}\/\d{2}$/', $expiration_date)) {
                $error = "Invalid card expiration date format (MM/YY).";
            }

            // Проверка достаточности средств для снятия
            if (isset($_POST['withdraw']) && $amount > $user['balance']) {
                $error = "Insufficient funds";
            }

            // Обновление баланса пользователя
            if (!isset($error)) {
                if (isset($_POST['deposit'])) {
                    $new_balance = $user['balance'] + $amount;
                } elseif (isset($_POST['withdraw'])) {
                    $new_balance = $user['balance'] - $amount;
                }

                $update_query = "UPDATE users SET balance = :new_balance WHERE id = :user_id";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':new_balance', $new_balance);
                $stmt->bindParam(':user_id', $user_id);

                // Выполнение обновления
                if ($stmt->execute()) {
                    // Перенаправление пользователя на профиль после успешного обновления баланса
                    header("Location: profile.php");
                    exit();
                } else {
                    $error = "Cound not update the balance";
                }
            }
        }
    }
}

// Закрытие соединения с базой данных
$pdo = null;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пополнение и снятие баланса</title>
    <link href="css/main.css" rel='stylesheet'>
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/1ov5nFfXq5lfhTbT6RcYzOgCwqUeJw/8U600k" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Подключение стилей Bulma -->
    <style>
        <?php navbar_settings_setup(); ?>
        <?php avatar_settings_setup(1); ?>
        <?php scrollbar_settings_setup(); ?>
        .card-form {
            padding: 5px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-left: 10%;
            margin-right: 5%;
        }
        .icon-user {
            margin-right: 5px;
        }
        body {
            background-color: #14161a;
        }
    </style>
</head>
<body>
    <?php nav_bar_show(); ?>
    <progress class="progress is-small is-primary" value="100" max="100">30%</progress>
        
    <div class="container">
        <section class="section">
            <div class="box card-form">
                <h2 class="title is-4">Balance managment</h2>
                <?php if (isset($error)): ?>
                    <div class="notification is-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <h3 class="subtitle is-3">Current Balance: <?php echo $user['balance']; ?> dollards.</h3>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                <!-- Форма для пополнения и снятия баланса -->
                <div class="box card-form">
                    <h2 class="title is-4">Card view</h2>
                    <!-- HTML разметка для карточки -->
                    <div class="field">
                        <label class="label">Card number:</label>
                        <div class="control">
                            <input class="input" type="text" id="card_number" name="card_number" maxlength="19" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password (3 digits):</label>
                        <div class="control">
                            <input class="input" type="password" id="password" name="password" maxlength="3" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Expiration Date (MM/YY):</label>
                        <div class="control">
                            <input class="input" type="text" id="expiration_date" name="expiration_date" pattern="\d{2}/\d{2}" placeholder="MM/YY" maxlength="5" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="amount">Sum:</label>
                        <div class="control">
                            <input class="input" type="number" id="amount" name="amount" min="1" required>
                        </div>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link" type="submit" name="deposit">Replenish funds</button>
                        </div>
                        <div class="control">
                            <button class="button is-light is-outlined" type="submit" name="withdraw">Withdraw funds</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </section>
    </div>
    <script>
    // Обработка полей ввода
    document.addEventListener('DOMContentLoaded', function() {
    var cardNumberInput = document.getElementById('card_number');
    var expirationDateInput = document.getElementById('expiration_date');
    var passwordInput = document.getElementById('password'); 
    var form = document.querySelector('form');

    // Обработчик для поля номера карты
    cardNumberInput.addEventListener('input', function(e) {
        var value = e.target.value;
        value = value.replace(/\D/g, '');
        value = value.replace(/(\d{4})/g, '$1 ');

        value = value.replace(/\s+/g, ' ').trim();

        e.target.value = value;
    });

    // Обработчик для поля даты окончания
    expirationDateInput.addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/\D/g, '');

        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }

        if (value.length > 5) {
            value = value.substring(0, 5);
        }

        e.target.value = value;
    });

    // Обработчик для поля пароля
    passwordInput.addEventListener('input', function(e) { 
            var value = e.target.value;

            if (/^\d+$/.test(value)) {
                e.target.value = value;
            } else {
                e.target.value = ''; 
            }
        });


    });
</script>
</body>
</html>
