<?php
require_once "db.php";

$error = "";

if (is_logged_in()) {
    header("Location: products.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $haslo = $_POST["haslo"];

    if (empty($login) || empty($haslo)) {
        $error = "Wypełnij wszystkie pola!";
    } else {
        $login_safe = mysqli_real_escape_string($conn, $login);
        $result = mysqli_query($conn, "SELECT id, login, haslo FROM uzytkownicy WHERE login='$login_safe'");

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($haslo, $row["haslo"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["login"] = $row["login"];
                header("Location: products.php");
                exit();
            } else {
                $error = "Błędny login lub hasło!";
            }
        } else {
            $error = "Błędny login lub hasło!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Logowanie</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>Login:</label>
            <input type="text" name="login" required>

            <label>Hasło:</label>
            <input type="password" name="haslo" required>

            <button type="submit">Zaloguj się</button>
        </form>

        <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
    </div>
</body>
</html>