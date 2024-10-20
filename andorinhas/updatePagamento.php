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
    $pgtoId = $data['pgtoId'] ?? null;
    $dia = $data['dia'] ?? null;
    $cfc = $data['cfc'] ?? null;
    $cd = $data['cd'] ?? null;
    $descricao = $data['descricao'] ?? null;
    $comp = $data['comp'] ?? null;
    $valor = $data['valor'] ?? null;
    $local_pgto = $data['local_pgto'] ?? null;
    $cp = $data['cp'] ?? null;
    $pago = isset($data['pago']) ? $data['pago'] : null; // Verifique se pago foi enviado
    $data_update = date('Y-m-d H:i:s');

    // Validar se todos os campos obrigatórios foram enviados corretamente
    if (!$dia) {
        echo json_encode(["success" => false, "message" => "Dia não fornecido corretamente."]);
        exit;
    } else if (!$cfc) {
        echo json_encode(["success" => false, "message" => "Cfc não fornecido corretamente."]);
        exit;
    } else if (!$cd) {
        echo json_encode(["success" => false, "message" => "Cd não fornecido corretamente."]);
        exit;
    } else if (!$descricao) {
        echo json_encode(["success" => false, "message" => "Descrição não fornecida corretamente."]);
        exit;
    } else if (!$comp) {
        echo json_encode(["success" => false, "message" => "Comp não fornecido corretamente."]);
        exit;
    } else if (!$valor) {
        echo json_encode(["success" => false, "message" => "Valor não fornecido corretamente."]);
        exit;
    } else if (!$local_pgto) {
        echo json_encode(["success" => false, "message" => "Local não fornecido corretamente."]);
        exit;
    } else if (!$cp) {
        echo json_encode(["success" => false, "message" => "Cp não fornecido corretamente."]);
        exit;
    } else if ($pago === null) { // Validação do checkbox (agora sempre será 1 ou 0)
        echo json_encode(["success" => false, "message" => "Pago não fornecido corretamente."]);
        exit;
    }

    // Deletar o pagamento do banco de dados (ajuste na query)
    $query = "UPDATE pagamentos SET dia = :dia, cfc = :cfc, cd = :cd, descricao = :descricao, comp = :comp, valor = :valor, local_pgto = :local_pgto, cp = :cp, pgto = :pago, data_update = :data_update WHERE id = :pgtoId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':pgtoId', $pgtoId);
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':cfc', $cfc);
    $stmt->bindParam(':cd', $cd);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':comp', $comp);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':local_pgto', $local_pgto);
    $stmt->bindParam(':cp', $cp);
    $stmt->bindParam(':pago', $pago, PDO::PARAM_INT); // Especifica que é um inteiro
    $stmt->bindParam(':data_update', $data_update);

    // Execução da query
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        $error = $stmt->errorInfo();
        echo json_encode(["success" => false, "message" => "Erro ao editar o pagamento: " . $error[2]]);
    }

?>
