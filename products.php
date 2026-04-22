<?php
require_once "db.php";
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Produkty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <h1>Wszystkie produkty</h1>

        <div class="search-box">
            <label>Szukaj produktu:</label>
            <input type="text" id="searchInput" placeholder="Wpisz nazwę...">
        </div>

        <table id="productsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa</th>
                    <th>Opis</th>
                    <th>Cena</th>
                    <th>Autor</th>
                    <th>Status</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.id, p.nazwa, p.opis, p.cena, p.status, u.login, p.user_id 
                        FROM produkty p 
                        JOIN uzytkownicy u ON p.user_id = u.id 
                        ORDER BY p.data_dodania DESC";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $isOwner = is_logged_in() && (int)$row["user_id"] === (int)get_user_id();
                        $isSold = $row["status"] === "sprzedany";
                        $canBuy = is_logged_in() && !$isOwner && !$isSold;

                        $statusClass = $isSold ? "sold" : "available";
                        $statusText = $isSold ? "sprzedany" : "dostępny";

                        echo "<tr class='product-row' data-name='" . htmlspecialchars(strtolower($row["nazwa"])) . "'>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . htmlspecialchars($row["nazwa"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["opis"]) . "</td>";
                        echo "<td>" . number_format($row["cena"], 2) . " zł</td>";
                        echo "<td>" . htmlspecialchars($row["login"]) . "</td>";
                        echo "<td><span class='status " . $statusClass . "'>" . $statusText . "</span></td>";
                        echo "<td>";

                        if ($canBuy) {
                            echo "<a href='buy.php?id=" . $row["id"] . "' class='btn-buy' onclick='return confirmCzyKupic(\"" . addslashes($row["nazwa"]) . "\")'>Kup</a>";
                        } elseif ($isSold) {
                            echo "<span class='btn-disabled'>Sprzedany</span>";
                        } elseif ($isOwner) {
                            echo "<span class='btn-disabled'>Twój produkt</span>";
                        } else {
                            echo "<a href='login.php' class='btn-login'>Zaloguj się aby kupić</a>";
                        }

                        echo "</td></tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='7'>Brak produktów.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="script.js"></script>
</body>
</html>