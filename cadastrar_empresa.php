<?php
require_once 'db.php'; // Inclua sua conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['empresaNome'];
    $caminho = $_POST['empresaCaminho'];

    try {
        $database = new Database(); // Sua classe de conexão
        $conn = $database->getConnection();

        // Prepare a consulta para inserção
        $query = "INSERT INTO empresa (nome, caminho) VALUES (:nome, :caminho)";
        $stmt = $conn->prepare($query);

        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':caminho', $caminho);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Empresa cadastrada com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao cadastrar a empresa."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Erro: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>
