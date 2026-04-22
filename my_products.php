<?php
require_once "db.php";
require_login();

$user_id = get_user_id();

// Obsługa usuwania
if (isset($_GET["delete"])) {
    $del_id = intval($_GET["delete"]);
    mysqli_query($conn, "DELETE FROM produkty WHERE id=$del_id AND user_id=$user_id AND status='dostepny'");
    header("Location: my_products.php");
    exit();
}

// Obsługa edycji
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_id"])) {
    $edit_id = intval($_POST["edit_id"]);
    $edit_nazwa = mysqli_real_escape_string($conn, trim($_POST["edit_nazwa"]));
    $edit_opis = mysqli_real_escape_string($conn, trim($_POST["edit_opis"]));
    $edit_cena = floatval($_POST["edit_cena"]);

    if (!empty($edit_nazwa) && $edit_cena > 0) {
        mysqli_query($conn, "UPDATE produkty SET nazwa='$edit_nazwa', opis='$edit_opis', cena=$edit_cena WHERE id=$edit_id AND user_id=$user_id");
    }
    header("Location: my_products.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM produkty WHERE user_id=$user_id ORDER BY data_dodania DESC");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Moje produkty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <h1>Moje produkty</h1>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa</th>
                    <th>Opis</th>
                    <th>Cena</th>
                    <th>Status</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . htmlspecialchars($row["nazwa"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["opis"]) . "</td>";
                    echo "<td>" . number_format($row["cena"], 2) . " zł</td>";
                    echo "<td><span class='status " . ($row["status"] === "sprzedany" ? "sold" : "available") . "'>" . $row["status"] . "</span></td>";
                    echo "<td>";

                    if ($row["status"] === "dostepny") {
                        echo "<a href='?edit=" . $row["id"] . "' class='btn-edit'>Edytuj</a> ";
                        echo "<a href='?delete=" . $row["id"] . "' class='btn-delete' onclick='return confirm(\"Czy na pewno usunąć?\")'>Usuń</a>";
                    } else {
                        echo "<span class='btn-disabled'>Sprzedany</span>";
                    }

                    echo "</td></tr>";
                    $i++;
                }

                if (mysqli_num_rows($result) === 0) {
                    echo "<tr><td colspan='6'>Nie masz jeszcze żadnych produktów.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        if (isset($_GET["edit"])) {
            $edit_id = intval($_GET["edit"]);
            $edit_result = mysqli_query($conn, "SELECT * FROM produkty WHERE id=$edit_id AND user_id=$user_id");
            $edit_row = mysqli_fetch_assoc($edit_result);

            if ($edit_row && $edit_row["status"] === "dostepny") {
        ?>
            <h2>Edytuj produkt</h2>
            <form method="POST" action="my_products.php">
                <input type="hidden" name="edit_id" value="<?= $edit_row["id"] ?>">
                <label>Nazwa:</label>
                <input type="text" name="edit_nazwa" value="<?= htmlspecialchars($edit_row["nazwa"]) ?>" required>

                <label>Opis:</label>
                <textarea name="edit_opis" rows="3"><?= htmlspecialchars($edit_row["opis"]) ?></textarea>

                <label>Cena:</label>
                <input type="number" name="edit_cena" step="0.01" value="<?= $edit_row["cena"] ?>" required>

                <button type="submit">Zapisz zmiany</button>
                <a href="my_products.php" class="btn-cancel">Anuluj</a>
            </form>
        <?php
            }
        }
        ?>

        <p><a href="add_product.php">+ Dodaj nowy produkt</a></p>
    </div>
</body>
</html>