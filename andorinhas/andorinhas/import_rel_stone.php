<?php
// Configurações do banco de dados
$servername = "localhost"; // Ou o endereço do seu servidor
$username = "root";
$password = "";
$dbname = "andorinhas";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Caminho para o arquivo CSV
$csvFile = 'relatorioStone.csv';

// Abrir o arquivo CSV
if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    // Preparar a consulta SQL
    $stmt = $conn->prepare("INSERT INTO stone (documento, stonecode, nome_fantasia, categoria, data_venda, data_vencimento, data_vencimento_original, bandeira, produto, stone_id, qtd_parcelas, num_parcelas, valor_bruto, valor_liquido, desconto_mdr, desconto_antecipacao, num_cartao, ultimo_status, data_ultimo_status, chave_externa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Verificar se a preparação da consulta foi bem-sucedida
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }


    // Ler cada linha do CSV
    while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
        // Assumindo que a ordem das colunas no CSV é a mesma da tabela
        $documento = $data[0];
        $stonecode = $data[1];
        $nome_fantasia = $data[2];
        $categoria = $data[3];
        $data_venda = $data[4];
        $data_vencimento = $data[5];
        $data_vencimento_original = $data[6];
        $bandeira = $data[7];
        $produto = $data[8];
        $stone_id = $data[9];
        $qtd_parcelas = $data[10];
        $num_parcelas = $data[11];
        $valor_bruto = $data[12];
        $valor_liquido = $data[13];
        $desconto_mdr = $data[14];
        $desconto_antecipacao = $data[15];
        $num_cartao = $data[16];
        $ultimo_status = $data[17];
        $data_ultimo_status = $data[18];
        $chave_externa = $data[19];


        $stmt->bind_param("sissssssssiissssssss", $documento, $stonecode, $nome_fantasia, $categoria, $data_venda, $data_vencimento, $data_vencimento_original, $bandeira, $produto, $stone_id, $qtd_parcelas, $num_parcelas, $valor_bruto, $valor_liquido, $desconto_mdr, $desconto_antecipacao, $num_cartao, $ultimo_status, $data_ultimo_status, $chave_externa);
        
        // Executar a consulta
        if (!$stmt->execute()) {
            echo "Erro ao inserir dados: " . $stmt->error . "\n";
        }
    }

    // Fechar a declaração
    $stmt->close();
    fclose($handle);
} else {
    echo "Não foi possível abrir o arquivo CSV.";
}

// Fechar a conexão
$conn->close();
?>