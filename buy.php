<?php
require_once "db.php";
require_login();

if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit();
}

$produkt_id = intval($_GET["id"]);
$kupujacy_id = get_user_id();


$result = mysqli_query($conn, "SELECT * FROM produkty WHERE id=$produkt_id");
$produkt = mysqli_fetch_assoc($result);

if (!$produkt) {
    $error = "Produkt nie istnieje!";
} elseif ($produkt["user_id"] == $kupujacy_id) {
    $error = "Nie możesz kupić własnego produktu!";
} elseif ($produkt["status"] === "sprzedany") {
    $error = "Ten produkt został już sprzedany!";
} else {
   
    mysqli_begin_transaction($conn);

    $success = false;
    try {
       
        $sql1 = "UPDATE produkty SET status='sprzedany' WHERE id=$produkt_id";
        mysqli_query($conn, $sql1);

        
        $sql2 = "INSERT INTO zakupy (produkt_id, kupujacy_id, data_zakupu) 
                 VALUES ($produkt_id, $kupujacy_id, NOW())";
        mysqli_query($conn, $sql2);

        mysqli_commit($conn);
        $success = true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Błąd podczas zakupu!";
    }

    if ($success) {
        $success_msg = "Kupiłeś produkt: " . htmlspecialchars($produkt["nazwa"]);
    }
}
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

        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($success_msg)): ?>
            <p class="success"><?= $success_msg ?></p>
        <?php endif; ?>

        <p><a href="products.php">← Wróć do produktów</a></p>
        <p><a href="purchase_history.php">→ Historia zakupów</a></p>
    </div>
</body>
</html>