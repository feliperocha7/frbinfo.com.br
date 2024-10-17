<?php
session_start(); // Inicia a sessão


require_once 'auth.php'; // Inclui a classe Auth
$auth = new Auth(); // Cria uma instância da classe Auth
$auth->checkSession(); // Verifica se a sessão está ativa
require_once 'db.php'; // Inclui a classe Database aqui

?>
