<?php
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $email = trim($_POST["email"]);
    $haslo = $_POST["haslo"];
    $haslo_powtorz = $_POST["haslo_powtorz"];

    if (empty($login) || empty($email) || empty($haslo)) {
        $error = "Wypełnij wszystkie pola!";
    } elseif ($haslo !== $haslo_powtorz) {
        $error = "Hasła nie są identyczne!";
    } elseif (strlen($haslo) < 6) {
        $error = "Hasło musi mieć min. 6 znaków!";
    } else {
        $login_safe = mysqli_real_escape_string($conn, $login);
        $email_safe = mysqli_real_escape_string($conn, $email);

        $check = mysqli_query($conn, "SELECT id FROM uzytkownicy WHERE login='$login_safe' OR email='$email_safe'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Taki login lub email już istnieje!";
        } else {
            $hash = password_hash($haslo, PASSWORD_DEFAULT);
            $sql = "INSERT INTO uzytkownicy (login, haslo, email) VALUES ('$login_safe', '$hash', '$email_safe')";
            if (mysqli_query($conn, $sql)) {
                $success = "Konto założone! Możesz się zalogować.";
            } else {
                $error = "Błąd podczas rejestracji.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Rejestracja</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm">
            <label>Login:</label>
            <input type="text" name="login" id="regLogin" required minlength="3">
            <span class="field-error" id="regLoginError"></span>

            <label>Email:</label>
            <input type="email" name="email" id="regEmail" required>
            <span class="field-error" id="regEmailError"></span>

            <label>Hasło:</label>
            <input type="password" name="haslo" id="regHaslo" required minlength="6">
            <span class="field-error" id="regHasloError"></span>

            <label>Powtórz hasło:</label>
            <input type="password" name="haslo_powtorz" id="regHasloPowtorz" required>
            <span class="field-error" id="regHasloPowtorzError"></span>

            <button type="submit">Zarejestruj się</button>
        </form>

        <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>