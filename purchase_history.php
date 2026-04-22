<?php
require_once "db.php";
require_login();

$kupujacy_id = get_user_id();

$result = mysqli_query($conn, 
    "SELECT z.id, p.nazwa, p.cena, z.data_zakupu, u.login AS sprzedawca
     FROM zakupy z
     JOIN produkty p ON z.produkt_id = p.id
     JOIN uzytkownicy u ON p.user_id = u.id
     WHERE z.kupujacy_id = $kupujacy_id
     ORDER BY z.data_zakupu DESC"
);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Historia zakupów</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <h1>Moje zakupy</h1>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produkt</th>
                    <th>Cena</th>
                    <th>Sprzedawca</th>
                    <th>Data zakupu</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . htmlspecialchars($row["nazwa"]) . "</td>";
                        echo "<td>" . number_format($row["cena"], 2) . " zł</td>";
                        echo "<td>" . htmlspecialchars($row["sprzedawca"]) . "</td>";
                        echo "<td>" . $row["data_zakupu"] . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='5'>Brak zakupów.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>