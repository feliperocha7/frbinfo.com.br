<nav class="navbar">
    <a href="dashboard.php" class="navbar-logo">
        <img src="logo3P.png" alt="FBI - Felipe Barros Informática Logo" class="navbar-logo-img">
    </a>
    <div class="navbar-buttons">
        <button class="nav-btn" onclick="window.location.href='dashboard.php';">Painel</button>
        <button class="nav-btn" onclick="window.location.href='bix/painel.php';">Bix</button>
        <button class="nav-btn" onclick="window.location.href='empsilva/dashboard.php';">Empsilva</button>
    </div>
    <div class="navbar-user">
        <span class="user-name">Olá, <?php echo $_SESSION['user']; ?></span>
        <button class="btn-settings" onclick="window.location.href='settings.php'">Configurações</button>
        <a href="logout.php" class="btn-logout">Sair</a>
    </div>
</nav>
 
