<?php
session_set_cookie_params([
    'path' => '/',               // Garante que a sessão seja válida em todas as pastas
    'httponly' => true,          // Protege contra XSS
    'samesite' => 'Strict'       // Evita o uso da sessão em sites externos
]);

session_start();  // Inicia a sessão com os parâmetros acima

require_once 'auth.php';  // Inclui a classe Auth
$auth = new Auth();       // Cria uma instância da classe Auth

if (!$auth->checkSession()) {
    error_log("Sessão inválida, redirecionando para login...");
}

require_once 'db.php';    // Inclui a classe Database aqui
