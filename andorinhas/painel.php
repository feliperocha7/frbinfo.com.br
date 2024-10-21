<?php
require_once '../session_check.php';
require_once 'db.php';

// Verifica se o produto é permitido
if ($_SESSION['produto'] !== 2 && $_SESSION['produto'] !== 0) {
    header('Location: ../valida_produto.php');
    exit(); // Adiciona exit para garantir que a execução pare aqui
}

// Conexão com o banco de dados
$database = new DatabaseAndorinhas();
$conn = $database->getConnection();

try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE dia = DAY(CURRENT_DATE()) AND comp = MONTH(CURRENT_DATE()) AND pgto = 0 ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_dia = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}

try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE dia = DAY(CURRENT_DATE()) AND comp = MONTH(CURRENT_DATE()) AND pgto = 1 ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_dia_pago = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}

try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE dia <= DAY(CURRENT_DATE()) AND comp = MONTH(CURRENT_DATE()) AND pgto = 0 ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_atrasada = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}


try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE WEEKOFYEAR(CONCAT_WS('-', '2024', comp, dia)) = WEEKOFYEAR(CURRENT_DATE()) AND pgto = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_semana = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}


try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE WEEKOFYEAR(CONCAT_WS('-', '2024', comp, dia)) = WEEKOFYEAR(CURRENT_DATE()) AND pgto = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_semana_pago = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}

try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE comp = MONTH(CURRENT_DATE()) AND pgto = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_mes = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}

try {
    // Consulta SQL
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE comp = MONTH(CURRENT_DATE()) AND pgto = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetching the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_mes_pago = $row['total'] ? $row['total'] : 0; // Caso não haja resultados, define como 0

    // Exibindo o total
    //echo "Total de pagamentos para hoje: R$ " . number_format($total, 2, ',', '.');

} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao consultar os dados: " . $e->getMessage();
}






// Fechando a conexão
$conn = null; // Fecha a conexão ao definir como null
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
    <?php include 'navbar.php'; ?>
    <div class="d-flex flex-column shadow-lg p-5 rounded justify-content-around">
        <div class="row justify-content-around p-2"> 
            <div class="col shadow-sm p-4 rounded col-lg-2 bg-danger text-warning text-center">
                <?php echo "Pagamentos atrasados:<br><center> R$ " . number_format($total_atrasada, 2, ',', '.') . "</center>"; ?>
            </div>   
            <div class="col shadow-sm p-4 rounded col-lg-3 bg-danger text-warning text-center">
                <?php echo "Pagamentos para hoje:<br><center>    R$ " . number_format($total_dia, 2, ',', '.') . "</center>"; ?>
            </div>
            <div class="col shadow-sm p-4 rounded col-lg-3 bg-danger text-warning text-center">
                <?php echo "Pagamentos da semana:<br><center> R$ " . number_format($total_semana, 2, ',', '.') . "</center>"; ?>
            </div>
            <div class="col shadow-sm p-4 rounded col-lg-2 bg-danger text-warning text-center">
                <?php echo "Pagamentos do mês:<br><center> R$ " . number_format($total_mes, 2, ',', '.') . "</center>"; ?>
            </div>
        </div>
        <div class="row justify-content-around p-2">
            <div class="col shadow-sm p-4 rounded col-lg-3 bg-success text-white text-center">
                <?php echo "Pagamentos pagos de hoje:<br><center> R$ " . number_format($total_dia_pago, 2, ',', '.') . "</center>"; ?>
            </div>
            <div class="col shadow-sm p-4 rounded col-lg-5 bg-success text-white text-center">
                <?php echo "Pagamentos pagos da semana:<br><center> R$ " . number_format($total_semana_pago, 2, ',', '.') . "</center>"; ?>
            </div>
            <div class="col shadow-sm p-4 rounded col-lg-3 bg-success text-white text-center">
                <?php echo "Pagamentos pagos do mês:<br><center> R$ " . number_format($total_mes_pago, 2, ',', '.') . "</center>"; ?>
            </div>
        </div>
    </div>    
    <?php include '../bootstrap_js.php'; ?>
</body>
</html>

