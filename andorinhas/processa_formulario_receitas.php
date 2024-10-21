<?php
session_start();

require_once 'db.php';

$database = new DatabaseAndorinhas();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $dia = $_POST['dia'];
    $comp = $_POST['comp'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $banco = $_POST['banco'];
    $cod = $_POST['cod'];
    $valor = str_replace(',', '.', $valor); // Converte vírgulas em pontos, se necessário

    // Verifica se as variáveis de sessão estão setadas 
    if (!isset($_SESSION['loja']) || !isset($_SESSION['user_id'])) {
        echo "ID da loja ou ID do usuário não está disponível.";
        exit;
    }

    // Captura id_loja e id_user da sessão  
    $id_loja = $_SESSION['loja'];
    $id_user = $_SESSION['user_id'];

    // Cria a data de lançamento com o formato desejado
    $data_cadastro = date('Y-m-d H:i:s'); // Mudei o formato para compatibilidade com DATETIME

    // Validação simples (vAdapterManager pode expandir conforme douche)
    if (empty($dia) || empty($comp) || empty($descricao) || empty($valor) || empty($banco) || empty($cod) || empty($id_loja) || empty($id_user)) {
        echo "Todos os campos são obrigatórios.";
        exit;   
    }

    // Prepara a chamada da procedure
    $stmt = $conn->prepare("CALL insert_receitas(:dia, :comp, :descricao, :valor, :banco, :cod, :data_cadastro, :id_loja, :id_user)");
    $stmt->bindParam(':dia', $dia);
    $stmt->bindParam(':comp', $comp);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':banco', $banco);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':data_cadastro', $data_cadastro);
    $stmt->bindParam(':id_loja', $id_loja);
    $stmt->bindParam(':id_user', $id_user);

    // Executa a chamada da procedure e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        echo "<script>alert('Receita adicionada com sucesso!');</script>";
        echo "<meta http-equiv='refresh' content='0;url=receitas.php'>";
    } else {
        echo "Erro ao adicionar receita: " . $stmt->errorInfo()[2];
    }
    }
    $conn = null;
?>