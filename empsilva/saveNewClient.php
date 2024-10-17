<?php
// Inclusões obrigatórias no início do corpo do documento
require_once '../session_check.php';
require_once 'db.php';

// Conexão com o banco de dados
$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['newClientName'];
    $cpf = $_POST['newClientCpf'];
    $indicator = $_POST['newClientIndicator'];
    $idUsuario = $_SESSION['user_id'];
    $emprestimoAtivo = "0";
    $ativo = "1";   

    // Definindo valores padrão para os campos de documento e residência
    $documentName = null;
    $residenceName = null;

    // Verificação de arquivos
    if (isset($_FILES['newClientDocument']) && $_FILES['newClientDocument']['error'] === UPLOAD_ERR_OK) {
        $documentDirectory = 'uploads/documentos/';
        $document = $_FILES['newClientDocument'];
        $documentName = $cpf . '-' . $name . '-document.' . pathinfo($document['name'], PATHINFO_EXTENSION);
        $documentPath = $documentDirectory . $documentName;
        if (!move_uploaded_file($document['tmp_name'], $documentPath)) {
            echo json_encode(["success" => false, "message" => "Erro ao fazer o upload do documento."]);
            exit();
        }
    }

    if (isset($_FILES['newClientResidence']) && $_FILES['newClientResidence']['error'] === UPLOAD_ERR_OK) {
        $residenceDirectory = 'uploads/comprovantes_residencia/';
        $residence = $_FILES['newClientResidence'];
        $residenceName = $cpf . '-' . $name . '-residence.' . pathinfo($residence['name'], PATHINFO_EXTENSION);
        $residencePath = $residenceDirectory . $residenceName;
        if (!move_uploaded_file($residence['tmp_name'], $residencePath)) {
            echo json_encode(["success" => false, "message" => "Erro ao fazer o upload do comprovante de residência."]);
            exit();
        }
    }

    // Inserção no banco de dados (arquivos são opcionais)
    $query = "INSERT INTO clientes (nome, cpf, documento, comprovante_residencia, indicacao, id_usuario, emprestimo_ativo, ativo) 
              VALUES (:name, :cpf, :document, :residence, :indicacao, :idUsuario, :emprestimo_ativo, :ativo)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':document', $documentName);
    $stmt->bindParam(':residence', $residenceName);
    $stmt->bindParam(':indicacao', $indicator);
    $stmt->bindParam(':idUsuario', $idUsuario);
    $stmt->bindParam(':emprestimo_ativo', $emprestimoAtivo );
    $stmt->bindParam(':ativo', $ativo);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao salvar no banco de dados."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}
?>