<nav class="navbar">
    <div class="nav-brand">🛒 Marketplace</div>
    <ul class="nav-links">
        <li><a href="products.php">Produkty</a></li>
        <li><a href="users.php">Użytkownicy</a></li>
        <?php if (is_logged_in()): ?>
            <li><a href="add_product.php">Dodaj produkt</a></li>
            <li><a href="my_products.php">Moje produkty</a></li>
            <li><a href="purchase_history.php">Historia zakupów</a></li>
            <li class="nav-user">
                Witaj, <strong><?= htmlspecialchars(get_user_login()) ?></strong>!
                <a href="logout.php" class="btn-logout">Wyloguj</a>
            </li>
        <?php else: ?>
            <li><a href="login.php">Zaloguj się</a></li>
            <li><a href="register.php">Zarejestruj się</a></li>
        <?php endif; ?>
    </ul>
</nav>