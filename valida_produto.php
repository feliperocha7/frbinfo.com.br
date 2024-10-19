<?php
require_once 'session_check.php';

// verifica o perfil do usuário
if ($_SESSION['produto'] == '1') {
    header('Location: empsilva/dashboard.php');
} else if ($_SESSION['produto'] == '2') {
    header('Location: andorinhas/painel.php');
}else{
    
}

?>
