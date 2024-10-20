<?php
require_once '../session_check.php';

if($_SESSION['produto'] !== 1 && $_SESSION['produto'] !== 0){
    header('Location: ../valida_produto.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link rel="stylesheet" href="style1.css"> <!-- Usando o estilo padrão -->
    <?php include '../bootstrap.php'; ?>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container shadow-lg p-3 mb-5 bg-body rounded">
        
    </div>
    <?php include '../bootstrap_js.php'; ?>
</body>
</html>