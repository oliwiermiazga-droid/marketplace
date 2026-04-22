<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "marketplace";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_login() {
    return $_SESSION['login'] ?? '';
}
?>