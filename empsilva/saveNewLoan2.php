<?php 
require_once '../session_check.php';
require_once 'db.php';

$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientId = $_POST['clientId'];
    $loanAmount = $_POST['loanAmount'];
    $loanDuration = $_POST['loanDuration'];
    $userId = $_SESSION['user_id'];
    $dataCadastro = date('Y-m-d H:i:s');
    $valorAberto = $loanAmount; // Valor total do empréstimo

    // Validação simples
    if (!is_numeric($loanAmount) || !is_numeric($loanDuration)) {
        echo json_encode(["success" => false, "message" => "Valores inválidos."]);
        exit;
    }
    
    $emprestimoAtivo = 1;
    // Insere o empréstimo
    $query = "INSERT INTO emprestimos (id_cliente, valor_total, duracao_meses, valor_aberto, id_usuario, data_cadastro, estado) VALUES (:clientId, :loanAmount, :loanDuration, :valorAberto, :userId, :dataCadastro, :estado)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':clientId', $clientId);
    $stmt->bindParam(':loanAmount', $loanAmount);
    $stmt->bindParam(':loanDuration', $loanDuration);
    $stmt->bindParam(':valorAberto', $valorAberto);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':dataCadastro', $dataCadastro);
    $stmt->bindParam(':estado', $emprestimoAtivo);
    
    $emprestimoAtivo = 1;
    // Atualiza o cliente para indicar que tem um empréstimo ativo
    $query1 = "UPDATE clientes SET emprestimo_ativo = :emprestimoAtivo WHERE id = :clientId";
    $stmtEmprestimo = $conn->prepare($query1);
    $stmtEmprestimo->bindParam(':emprestimoAtivo', $emprestimoAtivo);
    $stmtEmprestimo->bindParam(':clientId', $clientId);
    

    if ($stmt->execute()) {
        $loanId = $conn->lastInsertId(); // Obtém o ID do empréstimo recém-criado
        $monthlyPayment = $loanAmount / $loanDuration; // Calcula o valor da parcela

        for ($i = 1; $i <= $loanDuration; $i++) {
            $dueDate = date('Y-m-d', strtotime("+$i month", strtotime($dataCadastro))); // Data de vencimento
            $queryParcelas = "INSERT INTO parcelas (id_emprestimo, numero_parcela, valor, data_vencimento) VALUES (:loanId, :numPayment, :monthlyPayment, :dueDate)";
            $stmtParcelas = $conn->prepare($queryParcelas);
            $stmtParcelas->bindParam(':loanId', $loanId);
            $stmtParcelas->bindParam(':numPayment', $i);
            $stmtParcelas->bindParam(':monthlyPayment', $monthlyPayment);
            $stmtParcelas->bindParam(':dueDate', $dueDate);
            $stmtParcelas->execute();
        }

        

        echo json_encode(["success" => true]);
    } else {
        error_log(print_r($stmt->errorInfo(), true));
        echo json_encode(["success" => false, "message" => "Erro ao salvar o empréstimo."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>
