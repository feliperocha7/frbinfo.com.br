<?php
require_once '../session_check.php';
require_once 'db.php';

// Conexão com o banco de dados
$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loanId'])) {
    $loanId = $_POST['loanId'];

    // Obter detalhes do empréstimo
    $query = "SELECT valor_total, duracao_meses, valor_aberto FROM emprestimos WHERE id = :loanId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':loanId', $loanId);
    $stmt->execute();
    $loanDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($loanDetails) {
        $installments = [];
        $totalOpenValue = $loanDetails['valor_aberto'];
        $monthlyInstallment = $loanDetails['valor_total'] / $loanDetails['duracao_meses'];
        
        for ($i = 0; $i < $loanDetails['duracao_meses']; $i++) {
            $installmentValue = $monthlyInstallment * 1.15; // 15% de juros
            $dueDate = date('Y-m-d', strtotime("+$i month"));
            $installments[] = [
                'valorComJuros' => $installmentValue,
                'dataVencimento' => $dueDate
            ];
        }

        // Contar parcelas pagas
        $queryPaid = "SELECT COUNT(*) FROM pagamentos WHERE id_emprestimo = :loanId";
        $stmtPaid = $conn->prepare($queryPaid);
        $stmtPaid->bindParam(':loanId', $loanId);
        $stmtPaid->execute();
        $paidCount = $stmtPaid->fetchColumn();

        echo json_encode([
            'success' => true,
            'installments' => $installments,
            'totalOpenValue' => $totalOpenValue,
            'paidInstallments' => $paidCount,
            'openInstallments' => $loanDetails['duracao_meses'] - $paidCount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Empréstimo não encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>
