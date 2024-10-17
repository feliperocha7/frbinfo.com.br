<?php
require_once '../session_check.php';
require_once 'db.php';

$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payments']) && isset($_POST['loanId'])) {
    $payments = json_decode($_POST['payments'], true);
    $loanId = $_POST['loanId'];
    $totalDeduction = 0;
    $dataCadastro = date('Y-m-d H:i:s');

    try {
        // Inicia uma transação
        $conn->beginTransaction();

        foreach ($payments as $payment) {
            // Verifica se o ID e o valor estão definidos e são válidos
            if (isset($payment['id'], $payment['value']) && is_numeric($payment['value'])) {
                // Verifica se a parcela existe e se não está paga
                $checkStmt = $conn->prepare("SELECT pago FROM parcelas WHERE id = :id");
                $checkStmt->bindParam(':id', $payment['id']);
                $checkStmt->execute();
                $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if ($result && !$result['pago']) {
                    $stmt = $conn->prepare("UPDATE parcelas SET pago = 'pago', data_pgto = :data_pgto WHERE id = :id");
                    $stmt->bindParam(':data_pgto', $dataCadastro);
                    $stmt->bindParam(':id', $payment['id']);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Erro ao atualizar a parcela com ID {$payment['id']}: " . implode(", ", $stmt->errorInfo()));
                    }

                    $totalDeduction += $payment['value'];
                } else {
                    throw new Exception("A parcela com ID {$payment['id']} já está paga ou não existe.");
                }
            } else {
                throw new Exception("Dados de pagamento inválidos para a parcela com ID {$payment['id']}.");
            }
        }

        // Atualiza o valor aberto do empréstimo
        $updateStmt = $conn->prepare("UPDATE emprestimos SET valor_aberto = valor_aberto - :deduction WHERE id = :loanId");
        $updateStmt->bindParam(':deduction', $totalDeduction);
        $updateStmt->bindParam(':loanId', $loanId);

        if (!$updateStmt->execute()) {
            throw new Exception("Erro ao atualizar o valor do empréstimo: " . implode(", ", $updateStmt->errorInfo()));
        }


        //PROCEDURE que atualiza valor estado para 0 da tabela emprestimos para não aparecer mais o emprestimo em emprestimos.php
        //atualiza tambem emprestimo_ativo para 0 em clientes para o cliente ter linha de credito ativa novamente
        //primeiro ela pega o id do cliente na tabela emprestimos com valor_aberto = 0 e atualiza as tabelas das instruções acima
        $updateEmpretimos = $conn->prepare("CALL atualiza_emprestimos()");
        $updateEmpretimos->execute();
        
        if (!$updateEmpretimos->execute()) {
           throw new Exception("Erro ao rodar PROCEDURE atualiza_emprestimos(): " . implode(", ", $updateEmpretimos->errorInfo()));
        }

        // Confirma a transação
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Reverte a transação em caso de erro
        $conn->rollBack();
        error_log($e->getMessage()); // Log da mensagem de erro
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>