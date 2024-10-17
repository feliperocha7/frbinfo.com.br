<?php 
require_once 'session_check.php'; // Certifique-se de que isso é necessário para sua aplicação
require_once 'db.php'; // Inclua sua conexão com o banco de dados
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $usuario = $_POST['usuario'];
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $empresa_id = $_POST['produto']; // Corrigido para 'empresa_id'
    $perfil = $_POST['perfil'];

    // Inicializa a variável de resposta
    $response = ['success' => false];

    // Verifica se o ID do usuário é válido
    if (empty($user_id) || empty($usuario) || empty($senha_atual) || empty($empresa_id) || empty($perfil)) {
        $response['message'] = 'Todos os campos são obrigatórios.';
        echo json_encode($response);
        exit();
    }

    // Verifica se o usuário existe
    $query = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]); // Usando execute com array

    if ($stmt->rowCount() === 0) {
        $response['message'] = 'Usuário não encontrado.';
        echo json_encode($response);
        exit();
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica a senha atual
    if (!password_verify($senha_atual, $row['senha'])) {
        $response['message'] = 'Senha atual incorreta.';
        echo json_encode($response);
        exit();
    }

    // Atualiza o usuário
    if (!empty($nova_senha)) {
        // Se a nova senha for diferente da atual, atualize
        $nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT); // Hash a nova senha
        $updateQuery = "UPDATE usuarios SET usuario = ?, senha = ?, produto = ?, perfil = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$usuario, $nova_senha, $empresa_id, $perfil, $user_id]); // Usando execute com array
    } else {
        // Atualiza apenas nome de usuário, empresa e perfil
        $updateQuery = "UPDATE usuarios SET usuario = ?, produto = ?, perfil = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$usuario, $empresa_id, $perfil, $user_id]); // Usando execute com array
    }

    // Verifica se a atualização foi bem-sucedida
    if ($updateStmt->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = 'Usuário atualizado com sucesso!';
    } else {
        $response['message'] = 'Nenhuma alteração foi feita ou erro ao atualizar usuário.';
    }
} else {
    $response['message'] = 'Método inválido.';
}

echo json_encode($response);