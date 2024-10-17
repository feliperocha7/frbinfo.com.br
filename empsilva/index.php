<?php
session_start();
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $auth = new Auth();

    if ($auth->login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FBI - Felipe Barros Inform�tica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <!-- Adicione a tag <img> aqui -->
        <img src="img/logo.png" alt="Logo da FBI - Felipe Barros Informática" style="max-width: 100%; height: auto; margin-bottom: 20px;">

        <form action="index.php" method="POST" class="login-form">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
