<?php
require_once 'includes/config.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/main_view.php';
require_once 'includes/signup_view.php';
require_once 'includes/login_view.php';
require_once 'css/styled.php';
require_once 'scripts/feed.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Проверка наличия receiver_id в URL
if (!isset($_GET['receiver_id'])) {
    header("Location: profile.php");
    exit();
}

// Получение ID отправителя из сессии
$sender_id = $_SESSION['user_id'];

// Получение ID получателя из URL параметра
$receiver_id = $_GET['receiver_id'];

// Запрос информации о отправителе (баланс)
$query_sender = "SELECT * FROM users WHERE id = :sender_id";
$stmt_sender = $pdo->prepare($query_sender);
$stmt_sender->bindParam(':sender_id', $sender_id);
$stmt_sender->execute();
$sender = $stmt_sender->fetch(PDO::FETCH_ASSOC);

if (!$sender) {
    // Обработка ошибки, если отправитель не найден
    header("Location: profile.php");
    exit();
}

// Запрос информации о получателе (баланс)
$query_receiver = "SELECT * FROM users WHERE id = :receiver_id";
$stmt_receiver = $pdo->prepare($query_receiver);
$stmt_receiver->bindParam(':receiver_id', $receiver_id);
$stmt_receiver->execute();
$receiver = $stmt_receiver->fetch(PDO::FETCH_ASSOC);

if (!$receiver) {
    // Обработка ошибки, если получатель не найден
    header("Location: profile.php");
    exit();
}

// Обработка платежа при отправке формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['card_number'];
    $password = $_POST['password'];
    $expiration_date = $_POST['expiration_date'];

    if (isValidCardData($card_number, $password, $expiration_date)) {
        $amount = $_POST['amount'];

        if (!is_numeric($amount) || $amount <= 0) {
            $error = "Invalid amount.";
        } elseif ($amount > $sender['balance']) {
            $error = "Insufficient funds.";
        } else {
            $pdo->beginTransaction();

            try {
                $new_sender_balance = $sender['balance'] - $amount;
                $update_sender_query = "UPDATE users SET balance = :new_balance WHERE id = :sender_id";
                $stmt_update_sender = $pdo->prepare($update_sender_query);
                $stmt_update_sender->bindParam(':new_balance', $new_sender_balance);
                $stmt_update_sender->bindParam(':sender_id', $sender_id);
                $stmt_update_sender->execute();

                $new_receiver_balance = $receiver['balance'] + $amount;
                $update_receiver_query = "UPDATE users SET balance = :new_balance WHERE id = :receiver_id";
                $stmt_update_receiver = $pdo->prepare($update_receiver_query);
                $stmt_update_receiver->bindParam(':new_balance', $new_receiver_balance);
                $stmt_update_receiver->bindParam(':receiver_id', $receiver_id);
                $stmt_update_receiver->execute();

                $transaction_type = 'transfer';
                $transaction_time = date('Y-m-d H:i:s');
                $status = 'completed';

                $insert_transaction_query = "INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, transaction_time, status)
                                            VALUES (:sender_id, :receiver_id, :amount, :transaction_type, :transaction_time, :status)";
                $stmt_insert_transaction = $pdo->prepare($insert_transaction_query);
                $stmt_insert_transaction->bindParam(':sender_id', $sender_id);
                $stmt_insert_transaction->bindParam(':receiver_id', $receiver_id);
                $stmt_insert_transaction->bindParam(':amount', $amount);
                $stmt_insert_transaction->bindParam(':transaction_type', $transaction_type);
                $stmt_insert_transaction->bindParam(':transaction_time', $transaction_time);
                $stmt_insert_transaction->bindParam(':status', $status);
                $stmt_insert_transaction->execute();

                $pdo->commit();

                header("Location: profile.php");
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Transaction failed: " . $e->getMessage();
            }
        }
    } else {
        $error = "Invalid card data.";
    }
}

// Проверка введёных данных
function isValidCardData($card_number, $password, $expiration_date) {
    $clean_card_number = str_replace(' ', '', $card_number);

    if (strlen($clean_card_number)!== 16) {
        return false;
    }

    if (strlen($password)!== 3) {
        return false;
    }

    if (!preg_match("/^\d{2}\/\d{2}$/", $expiration_date)) {
        return false;
    }

    return true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Transfer</title>
    <link href="css/main.css" rel="stylesheet">
    <link href="css/bulma.css" rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/1ov5nFfXq5lfhTbT6RcYzOgCwqUeJw/8U600k" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    <?php navbar_settings_setup();?>
    <?php avatar_settings_setup(1);?>
    <?php scrollbar_settings_setup();?>
  .card-form {
        background-color: #f5f5f5;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
<?php nav_bar_show();?>
<progress class="progress is-small is-primary" value="100" max="100">30%</progress>
<div class="container">
    <section class="section">
        <div class="box">
            <h2 class="title is-4">Transfer Funds</h2>
            <?php if (isset($error)):?>
                <div class="notification is-danger"><?php echo $error;?></div>
            <?php endif;?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]). '?receiver_id='. $receiver_id;?>" method="POST">
                <div class="field">
                    <label class="label">Amount:</label>
                    <div class="control">
                        <input class="input" type="number" id="amount" name="amount" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Recipient Name:</label>
                    <div class="control">
                        <input class="input" type="text" id="recipient_name" name="recipient_name" value="<?php echo htmlspecialchars($receiver['NAME']);?>" readonly>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Card Number:</label>
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
                <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link" type="submit">Transfer</button>
                        </div>
                        <div class="control">
                            <a class="button is-light is-outlined" href="profile.php">Cancel</a>
                        </div>
                    </div>
            </form>
        </div>
    </section>
</div>
<script>
    // Обработка полей ввода
document.addEventListener('DOMContentLoaded', function() {
    var cardNumberInput = document.getElementById('card_number');
    var expirationDateInput = document.getElementById('expiration_date');
    var passwordInput = document.getElementById('password'); 

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
