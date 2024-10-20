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
        $id_receita = $data['id_receita'] ?? null; // Usar coalescência para evitar aviso

        if ($id_receita === null) {
            echo json_encode(["success" => false, "message" => "ID da receita não fornecido."]);
            exit;
        }

        // Deletar o cliente do banco de dados
        $query = "DELETE FROM receitas WHERE id = :id_receita";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_receita', $id_receita);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao excluir a receita."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Método não permitido."]);
    }
?>
