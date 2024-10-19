<?php
require_once '../session_check.php';

if($_SESSION['produto'] !== 2 && $_SESSION['produto'] !== 0){
    header('Location: ../valida_produto.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <?php include '../bootstrap.php'; ?>
    <title>Painel Bix</title>
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    
</body>
</html>

