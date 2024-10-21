<?php
    // Inclusões obrigatórias no início do corpo do documento
    require_once '../session_check.php';
    require_once 'db.php';

    // Conexão com o banco de dados
    $database = new DatabaseAndorinhas();
    $conn = $database->getConnection();

    // Verificando se o formulário foi enviado
    // Obter os dados da requisição (JSON no corpo da requisição)
    $data = json_decode(file_get_contents('php://input'), true);

    // Receber todos os campos enviados
    $id_receita = $data['receita_id'] ?? null;
    $dia = $data['dia'] ?? null;
    $descricao = $data['descricao'] ?? null;
    $comp = $data['comp'] ?? null;
    $valor = $data['valor'] ?? null;
    $banco = $data['banco'] ?? null;
    $cod = $data['cod'] ?? null;
    $id_loja = $data['loja'];
    $id_user = $_SESSION['user_id'];
    $data_update = date('Y-m-d H:i:s');

    // Validar se todos os campos obrigatórios foram enviados corretamente
    if (!$id_receita) {
        echo json_encode(["success" => false, "message" => "ID não fornecido corretamente."]);
        exit;
    }else if (!$dia) {
        echo json_encode(["success" => false, "message" => "Dia não fornecido corretamente."]);
        exit;
    } else if (!$comp) {
        echo json_encode(["success" => false, "message" => "Comp não fornecido corretamente."]);
        exit;
    }else if (!$descricao) {
        echo json_encode(["success" => false, "message" => "Comp não fornecido corretamente."]);
        exit;
    } else if (!$valor) {
        echo json_encode(["success" => false, "message" => "Valor não fornecido corretamente."]);
        exit;
    } else if (!$banco) {
        echo json_encode(["success" => false, "message" => "Local não fornecido corretamente."]);
        exit;
    } else if (!$cod) {
        echo json_encode(["success" => false, "message" => "Cp não fornecido corretamente."]);
        exit;
    }

    // Deletar o pagamento do banco de dados (ajuste na query)
    $query = "UPDATE receitas SET dia = :dia, comp = :comp, descricao = :descricao, valor = :valor, banco = :banco, cod = :cod, id_loja = :id_loja, id_user = :id_user, data_update = :data_update WHERE id = :id_receita";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_receita', $id_receita);
    $stmt->bindParam(':dia', $dia); 
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':comp', $comp);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':banco', $banco);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':id_loja', $id_loja);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':data_update', $data_update);

    // Execução da query
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        $error = $stmt->errorInfo();
        echo json_encode(["success" => false, "message" => "Erro ao editar o receita: " . $error[2]]);
    }

?>
