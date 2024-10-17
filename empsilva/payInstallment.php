<?php 
require_once '../session_check.php';
require_once 'db.php';

$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loanId = $_POST['loanId'];
    $installmentId = $_POST['installmentId'];

    // Atualiza o status da parcela para 'pago'
    $query = "UPDATE parcelas SET pago = 'pago' WHERE id = :installmentId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':installmentId', $installmentId);
    
    if ($stmt->execute()) {
        // Obtem o valor da parcela
        $queryInstallment = "SELECT valor FROM parcelas WHERE id = :installmentId";
        $stmtInstallment = $conn->prepare($queryInstallment);
        $stmtInstallment->bindParam(':installmentId', $installmentId);
        $stmtInstallment->execute();
        $installment = $stmtInstallment->fetch(PDO::FETCH_ASSOC);
        
        // Atualiza o valor_aberto do empréstimo
        $newValueOpen = $installment['valor']; // Assume que o valor é o que deve ser subtraído
        $queryUpdateLoan = "UPDATE emprestimos SET valor_aberto = valor_aberto - :newValueOpen WHERE id = :loanId";
        $stmtUpdateLoan = $conn->prepare($queryUpdateLoan);
        $stmtUpdateLoan->bindParam(':newValueOpen', $newValueOpen);
        $stmtUpdateLoan->bindParam(':loanId', $loanId);
        $stmtUpdateLoan->execute();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao pagar a parcela."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>