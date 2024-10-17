<?php
require_once '../session_check.php';
require_once 'db.php';

// Conexão com o banco de dados
$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loanId = $_POST['loanId'];
    $loanDuration = $_POST['loanDuration'];
    $loanAmount = $_POST['loanAmount'];

    // Atualizando o empréstimo
    $query = "UPDATE emprestimos SET duracao_meses = :loanDuration, valor_total = :loanAmount, valor_aberto = :loanAmount WHERE id = :loanId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':loanDuration', $loanDuration);
    $stmt->bindParam(':loanAmount', $loanAmount);
    $stmt->bindParam(':loanId', $loanId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar o empréstimo."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>
