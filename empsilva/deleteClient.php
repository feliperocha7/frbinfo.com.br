<?php
// Inclusões obrigatórias no início do corpo do documento
require_once '../session_check.php';
require_once 'db.php';

// Conexão com o banco de dados
$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtendo os dados da requisição
    $data = json_decode(file_get_contents('php://input'), true);
    $clientId = $data['clientId'] ?? null; // Usar coalescência para evitar aviso

    if ($clientId === null) {
        echo json_encode(["success" => false, "message" => "ID do cliente não fornecido."]);
        exit;
    }

    // Deletar o cliente do banco de dados
    $query = "DELETE FROM clientes WHERE id = :clientId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':clientId', $clientId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao excluir o cliente."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}

// // Inclusões obrigatórias no início do corpo do documento
// require_once 'verifica_login.php';
// require_once 'db.php';

// // Conexão com o banco de dados
// $database = new Database();
// $conn = $database->getConnection();

// // Verificando se o formulário foi enviado
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $clientId = $_POST['clientId'];

//     // Deletar o cliente do banco de dados
//     $query = "DELETE FROM clientes WHERE id = :clientId";
//     $stmt = $conn->prepare($query);
//     $stmt->bindParam(':clientId', $clientId);

//     if ($stmt->execute()) {
//         echo json_encode(["success" => true]);
//     } else {
//         echo json_encode(["success" => false, "message" => "Erro ao excluir o cliente."]);
//     }
// } else {
//     echo json_encode(["success" => false, "message" => "Método não permitido."]);
// }
?>
