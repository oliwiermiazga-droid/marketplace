<?php
require_once "db.php";
require_login();

if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit();
}

$produkt_id = intval($_GET["id"]);
$kupujacy_id = get_user_id();

$error = "";
$success_msg = "";


mysqli_begin_transaction($conn);

try {
    
    $sql_check = "SELECT user_id, status, nazwa FROM produkty WHERE id=$produkt_id";
    $result = mysqli_query($conn, $sql_check);
    $produkt = mysqli_fetch_assoc($result);

    if (!$produkt) {
        throw new Exception("Produkt nie istnieje!");
    }
    if ($produkt["user_id"] == $kupujacy_id) {
        throw new Exception("Nie możesz kupić własnego produktu!");
    }
    if ($produkt["status"] === "sprzedany") {
        throw new Exception("Ten produkt został już sprzedany!");
    }

    
    
    $sql_update = "UPDATE produkty SET status='sprzedany' WHERE id=$produkt_id AND status='dostepny'";
    mysqli_query($conn, $sql_update);

    
    
    if (mysqli_affected_rows($conn) === 0) {
        throw new Exception("Nie udało się kupić produktu – ktoś inny kupił go chwilę wcześniej!");
    }

    
    $sql_insert = "INSERT INTO zakupy (produkt_id, kupujacy_id, data_zakupu) 
                   VALUES ($produkt_id, $kupujacy_id, NOW())";
    mysqli_query($conn, $sql_insert);

    
    mysqli_commit($conn);
    $success_msg = "Gratulacje! Pomyślnie kupiłeś produkt: " . htmlspecialchars($produkt["nazwa"]);

} catch (Exception $e) {
    
    mysqli_rollback($conn);
    $error = $e->getMessage();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zakup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <h1>Potwierdzenie zakupu</h1>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success_msg)): ?>
            <p class="success"><?= htmlspecialchars($success_msg) ?></p>
        <?php endif; ?>

        <p><a href="products.php">← Wróć do produktów</a></p>
        <p><a href="purchase_history.php">→ Historia zakupów</a></p>
    </div>
</body>
</html>