<?php
require_once 'db.php'; // Inclua sua classe de conexão com o banco de dados

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $perfil = $_POST['perfil'];
    $empresaId = $_POST['empresaId']; // O ID da empresa selecionada

    // Aqui você pode adicionar a lógica para hash da senha antes de armazená-la
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $database = new Database();
        $conn = $database->getConnection();

        // Prepare a consulta para inserção
        $query = "INSERT INTO usuarios (usuario, senha, perfil, ativo, produto) VALUES (:usuario, :senha, :perfil, :ativo, :produto)";
        $stmt = $conn->prepare($query);

        // Bind dos parâmetros
        $ativo = 1; // O campo ativo é 1 ao cadastrar
        $stmt->bindParam(':usuario', $username);
        $stmt->bindParam(':senha', $hashedPassword);
        $stmt->bindParam(':perfil', $perfil);
        $stmt->bindParam(':ativo', $ativo);
        $stmt->bindParam(':produto', $empresaId); // ID da empresa

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Usuário cadastrado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao cadastrar o usuário."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Erro: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>
