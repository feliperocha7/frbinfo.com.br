<?php
require_once 'session_check.php';

// verifica o perfil do usuário
if ($_SESSION['produto'] == 1) {
    header('Location: /frbinfo.com.br/empsilva/dashboard.php');
} else if ($_SESSION['produto'] == 2) {
    header('Location: /frbinfo.com.br/andorinhas/painel.php');
}

?>
