<?php
require 'config.php'; // Arquivo de configuração do banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $perfil = $_POST['perfil'];

    // Inserir usuário no banco de dados
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, perfil) VALUES (:username, :password, :perfil)");
    if ($stmt->execute(['username' => $username, 'password' => $password, 'perfil' => $perfil])) {
        echo "Usuário registrado com sucesso!";
    } else {
        echo "Erro ao registrar o usuário.";
    }
}
?>

<form method="post">
    Nome de Usuário: <input type="text" name="username" required><br>
    Senha: <input type="password" name="password" required><br>
    Perfil: 
    <select name="perfil">
        <option value="usuario">Usuário</option>
        <option value="admin">Admin</option>
    </select><br>
    <input type="submit" value="Registrar">
</form>
