<?php
// Inicie a sessão para acessar as variáveis de sessão
session_start();

// Inclua o arquivo de conexão
require_once 'db.php'; // Certifique-se de que o caminho está correto

// Criar uma nova instância da classe DatabaseAndorinhas
$database = new DatabaseAndorinhas();
$conn = $database->getConnection();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $dia = $_POST['dia'];
    $cfc = $_POST['cfc'];
    $cd = $_POST['cd'];
    $descricao = $_POST['descricao'];
    $comp = $_POST['comp'];
    $valor = $_POST['valor'];
    $local_pgto = $_POST['local_pgto'];
    $cp = $_POST['cp'];
    $pgto = isset($_POST['pgto']) ? 1 : 0; // 1 para sim, 0 para não

    // Verifica se as variáveis de sessão estão setadas
    if (!isset($_SESSION['loja']) || !isset($_SESSION['user_id'])) {
        echo "ID da loja ou ID do usuário não está disponível.";
        exit;
    }

    // Captura id_loja e id_user da sessão
    $id_loja = $_SESSION['loja'];
    $id_user = $_SESSION['user_id'];

    // Cria a data de lançamento com o formato desejado
    $data_lancamento = date('Y-m-d H:i:s'); // Mudei o formato para compatibilidade com DATETIME

    // Validação simples (você pode expandir conforme necessário)
    if (empty($dia) || empty($cfc) || empty($cd) || empty($descricao) || empty($comp) || empty($valor) || empty($local_pgto) || empty($cp)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    // Preparar a chamada da procedure
    $sql = "CALL inserir_pagamento(:dia, :cfc, :cd, :descricao, :comp, :valor, :local_pgto, :cp, :pgto, :id_loja, :id_user, :data_lancamento)";
    
    $stmt = $conn->prepare($sql);

    // Bind dos parâmetros
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':cfc', $cfc);
    $stmt->bindParam(':cd', $cd);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':comp', $comp);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':local_pgto', $local_pgto);
    $stmt->bindParam(':cp', $cp);
    $stmt->bindParam(':pgto', $pgto);
    $stmt->bindParam(':id_loja', $id_loja);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':data_lancamento', $data_lancamento);

    // Executa a chamada da procedure e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        echo "<script>alert('Pagamento adicionado com sucesso!');</script>";
        echo "<meta http-equiv='refresh' content='0;url=pagamentos.php'>";
    } else {
        echo "Erro ao adicionar pagamento: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Método de requisição inválido.";
}

// Fechar a conexão
$conn = null;
?>
