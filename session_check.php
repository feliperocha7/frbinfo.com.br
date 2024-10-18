<?php
// Inicia a sessão
session_set_cookie_params([
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php'; // Ajuste o caminho conforme necessário
require_once 'auth.php'; // Inclui a classe Auth

// Cria uma instância da classe Auth
$auth = new Auth();

// Verifica a sessão
if (!$auth->checkSession()) {
    error_log("Sessão inválida, redirecionando para login...");
}
?>
