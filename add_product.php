<?php
require_once "db.php";
require_login();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nazwa = trim($_POST["nazwa"]);
    $opis = trim($_POST["opis"]);
    $cena = trim($_POST["cena"]);

    if (empty($nazwa) || empty($cena)) {
        $error = "Nazwa i cena są wymagane!";
    } elseif (!is_numeric($cena) || floatval($cena) <= 0) {
        $error = "Cena musi być dodatnią liczbą!";
    } else {
        $nazwa_safe = mysqli_real_escape_string($conn, $nazwa);
        $opis_safe = mysqli_real_escape_string($conn, $opis);
        $cena_safe = floatval($cena);
        $user_id = get_user_id();

        $sql = "INSERT INTO produkty (nazwa, opis, cena, user_id, status) 
                VALUES ('$nazwa_safe', '$opis_safe', $cena_safe, $user_id, 'dostepny')";

        if (mysqli_query($conn, $sql)) {
            $success = "Produkt został dodany!";
        } else {
            $error = "Błąd podczas dodawania produktu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj produkt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <h1>Dodaj nowy produkt</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="add_product.php" id="addProductForm">
            <label>Nazwa produktu:</label>
            <input type="text" name="nazwa" id="prodNazwa" required maxlength="100">
            <span class="field-error" id="prodNazwaError"></span>

            <label>Opis:</label>
            <textarea name="opis" id="prodOpis" rows="4" maxlength="500"></textarea>
            <span class="field-error" id="prodOpisError"></span>

            <label>Cena (zł):</label>
            <input type="number" name="cena" id="prodCena" step="0.01" min="0.01" required>
            <span class="field-error" id="prodCenaError"></span>

            <button type="submit">Dodaj produkt</button>
        </form>

        <p><a href="products.php">← Wróć do listy produktów</a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>