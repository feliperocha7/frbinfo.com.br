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

    try {
        // Chamada à procedure InserirEmprestimo
        $query = "CALL InserirEmprestimo(:clientId, :loanAmount, :loanDuration, :valorAberto, :userId, :dataCadastro)";
        $stmt = $conn->prepare($query);

        // Bind dos parâmetros
        $stmt->bindParam(':clientId', $clientId);
        $stmt->bindParam(':loanAmount', $loanAmount);
        $stmt->bindParam(':loanDuration', $loanDuration);
        $stmt->bindParam(':valorAberto', $valorAberto);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':dataCadastro', $dataCadastro);

        // Executa a procedure
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Empréstimo inserido com sucesso!"]);
        } else {
            $errorInfo = $stmt->errorInfo();  // Captura o erro SQL
            error_log(print_r($errorInfo, true));
            echo json_encode(["success" => false, "message" => "Erro ao salvar o empréstimo: " . $errorInfo[2]]);
        }

    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(["success" => false, "message" => "Erro ao processar a requisição: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>
