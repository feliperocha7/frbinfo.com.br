<nav class="navbar">
    <a href="painel.php" class="navbar-logo">
        <img src="logo3P.png" alt="FBI - Felipe Barros Informática Logo" class="navbar-logo-img">
    </a>
    <div class="navbar-buttons">
        <button class="nav-btn" onclick="window.location.href='painel.php';">Painel</button>
        <button class="nav-btn" onclick="window.location.href='andorinhas/painel.php';">Andorinhas</button>
        <button class="nav-btn" onclick="window.location.href='empsilva/painel.php';">Empsilva</button>
    </div>
    <div class="navbar-user">
        <span class="user-name">Olá, <?php echo $_SESSION['user']; ?></span>
        <button class="btn-settings" onclick="window.location.href='settings.php'">Configurações</button>
        <a href="logout.php" class="btn-logout">Sair</a>
    </div>
</nav>
 
