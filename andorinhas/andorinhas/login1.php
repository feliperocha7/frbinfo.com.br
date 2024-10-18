<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar se o usuário existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();
    $senha = md5($user['password']);

    if ($user && password_verify($password, $senha)) {
        // Armazenar informações na sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['perfil'] = $user['perfil'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Usuário ou senha inválidos." . $user['password'] . '   ' . md5($password);
        echo "<br>" . md5(12345);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
    <title>Login Andorinhas</title>
</head>
<body>
    <div class="container">
        <div class="login">
            <form method="post">
                Nome de Usuário: <input type="text" name="username" required><br>
                Senha: <input type="password" name="password" required><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
    
</body>
</html>
