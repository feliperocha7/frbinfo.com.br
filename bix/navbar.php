<nav class="navbar">
    <a href="painel.php" class="navbar-logo">
        <img src="img/logo3P.png" alt="FBI - Felipe Barros Informática Logo" class="navbar-logo-img">
    </a>
    <div class="navbar-buttons">
        <button class="nav-btn" onclick="window.location.href='painel.php';">Painel</button>
        <button class="nav-btn" onclick="window.location.href='pagamentos.php';">Pagamentos</button>
        <button class="nav-btn" onclick="window.location.href='receitas.php';">Receitas</button>
        <button class="nav-btn" onclick="window.location.href='caixas.php';">Caixas</button>
        <button class="nav-btn" onclick="window.location.href='uploads.php';">Uploads</button>
    </div>
    <div class="navbar-user">
        <span class="user-name">Olá, <?php echo $_SESSION['user']; ?></span>
        <button class="btn-settings" onclick="window.location.href='settings.php'">Configurações</button>
        <a href="../logout.php" class="btn-logout">Sair</a>
    </div>
</nav>

