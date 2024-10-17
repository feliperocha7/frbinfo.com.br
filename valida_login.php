<?php

// conexão com o banco de dados
require_once 'db.php';

// dados do formulário
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

// consulta ao banco de dados
$sql = "SELECT * FROM usuarios WHERE usuario = ? AND senha = ? AND ativo = '1'";
$stmt = $conn->prepare($sql);
$stmt->execute([$usuario, $senha]);

// verifica se o usuário existe e se a senha está correta
if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch();

    // verifica o perfil do usuário
    if ($row['perfil'] == '1') {
        // perfil de admin
        $_SESSION['perfil'] = 'admin';
    } else {
        // perfil de usuário
        $_SESSION['perfil'] = 'user';
    }

    // verifica qual produto pertence
    if ($row['produto'] == '1') {
        // produto 1
        $_SESSION['produto'] = '1';
    } else if ($row['produto'] == '2') {
        // produto 2
        $_SESSION['produto'] = '2';
    } else {
        // produto 3
        $_SESSION['produto'] = '3';
    }

    // salva a sessão
    $_SESSION['logado'] = true;
    $_SESSION['usuario'] = $usuario;

    // redireciona para a página correta
    if ($_SESSION['perfil'] == 'admin') {
        header('Location: painel.php');
    } else {
        header('Location: painel.php');
    }
} else {
    // erro de login
    $_SESSION['erro_login'] = 'Usuário ou senha inválidos!';
    header('Location: login.php');
}
