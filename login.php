<?php
session_start();
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $auth = new Auth();
    
    echo $_SESSION['produto'];
    if ($auth->login($username, $password)) {
        if($_SESSION['produto'] == 0){
            header("Location: painel.php");
            exit();
        }else if($_SESSION['produto'] == 1){
            header("Location: empsilva/dashboard.php");
            exit();
        }  
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
    <title>Login</title>
    <link rel="stylesheet" href="style-login.css">
</head>
<body>
    <div class="login-container">

        <img src="img/logo.png" alt="Logo da FBI - Felipe Barros InformÃ¡tica" style="max-width: 100%; height: auto; margin-bottom: 20px;">
        
        <form action="login.php" method="POST" class="login-form">
            <center><h2>Login</h2></center>
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
