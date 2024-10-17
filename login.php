<?php
    require_once 'session_check.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style-login.css">
</head>
<body>
    <div class="login-container">
        <img src="img/logo.png" alt="Logo da FBI - Felipe Barros Informática" style="max-width: 100%; height: auto; margin-bottom: 20px;">
        <form action="login.php" method="POST" class="login-form">
            <center><h2>Login</h2></center>
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
