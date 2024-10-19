<?php
//TESTEANDO SINCRONIZAÇÃO COM KINGHOST
session_start();
require_once 'auth.php'; // Importa a classe de autenticação

// Verifica se o método de requisição é POST (quando o formulário de login é enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Instancia a classe Auth (Autenticação)
    $auth = new Auth();

    // Tenta realizar o login
    if ($auth->login($username, $password)) {
        // Verifica o valor da variável de sessão 'produto' para redirecionar o usuário corretamente
        if ($_SESSION['produto'] == 0) {
            header("Location: ./painel.php"); // Redireciona para o painel geral
            exit();
        } elseif ($_SESSION['produto'] == 1) {
            header("Location: ./empsilva/dashboard.php"); // Redireciona para o painel específico
            exit();
        } elseif ($_SESSION['produto'] == 2) {
            header("Location: ./andorinhas/painel.php"); // Redireciona para o painel específico
            exit();
        }
    } else {
        // Exibe mensagem de erro se o login falhar
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
    <link rel="stylesheet" href="style-login.css"> <!-- Arquivo CSS para estilização -->
</head>
<body>
    <div class="login-container">
        <!-- Logotipo -->
        <img src="img/logo.png" alt="Logo da FBI - Felipe Barros Informática" style="max-width: 100%; height: auto; margin-bottom: 20px;">

        <!-- Formulário de Login -->
        <form action="login.php" method="POST" class="login-form">
            <center><h2>Login</h2></center>
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
