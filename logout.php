<?php
// Inicia a sessão
session_start();

// Inclua o arquivo com a classe Auth
require_once 'auth.php';

// Crie uma instância da classe Auth
$auth = new Auth();

// Chame o método logout
$auth->logout();
?>
