<?php
require_once '../session_check.php';
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

if (isset($_POST['loanId'])) {
    $loanId = $_POST['loanId'];

    // Consulta para obter as parcelas do empréstimo
    $query = "SELECT id, numero_parcela, valor, data_vencimento, pago FROM parcelas WHERE id_emprestimo = :loanId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':loanId', $loanId);
    $stmt->execute();

    $installments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retorna as parcelas como JSON
    echo json_encode($installments);
} else {
    // Caso o ID do empréstimo não seja passado
    echo json_encode([]);
}
?>

