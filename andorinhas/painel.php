<?php
require_once '../session_check.php';

if($_SESSION['produto'] !== 2 || $_SESSION['produto'] !== 0){
    header('Location: ../valida_produto.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <title>Painel Bix</title>
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    
</body>
</html>

