<?php
require_once '../session_check.php';
require_once 'db.php';

// Conexão com o banco de dados
$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $loanId = $data['loanId'];

    // Deletando o empréstimo
    $query = "DELETE FROM emprestimos WHERE id = :loanId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':loanId', $loanId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao excluir o empréstimo."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>
