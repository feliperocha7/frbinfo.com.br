<?php
    // Inclusões obrigatórias no início do corpo do documento
    require_once '../session_check.php';
    require_once 'db.php';

    // Conexão com o banco de dados
    $database = new DatabaseAndorinhas();
    $conn = $database->getConnection();

    // Verificando se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtendo os dados da requisição
        $data = json_decode(file_get_contents('php://input'), true);
        $pgtoId = $data['pgtoId'] ?? null; // Usar coalescência para evitar aviso

        if ($pgtoId === null) {
            echo json_encode(["success" => false, "message" => "ID do cliente não fornecido."]);
            exit;
        }

        // Deletar o cliente do banco de dados
        $query = "DELETE FROM pagamentos WHERE id = :pgtoId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':pgtoId', $pgtoId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao excluir o cliente."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Método não permitido."]);
    }
?>
