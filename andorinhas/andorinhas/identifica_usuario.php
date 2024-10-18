<?php 

session_start();
echo '<div class="container">';
echo '<div class="usuario">';
// Exibir informações do usuário
echo "Bem-vindo, " . $_SESSION['username'] . "!<br>";
echo "Seu perfil: " . $_SESSION['perfil'] . "<br>";

// Controle de acesso baseado no perfil
if ($_SESSION['perfil'] === 'admin') {
    echo "Você tem acesso ao painel de administração.";
    // Aqui você pode adicionar funcionalidades específicas para administradores
} else {
    echo "Você é um usuário comum.";
    // Funcionalidades para usuários comuns
}

// Logout
echo '<br><a href="logout.php">Logout</a>';
echo '</div>';
?>