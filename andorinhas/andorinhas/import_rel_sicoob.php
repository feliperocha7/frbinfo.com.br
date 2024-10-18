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
$csvFile = 'relatorioSicoob.csv';

// Abrir o arquivo CSV
if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    // Preparar a consulta SQL
    $stmt = $conn->prepare("INSERT INTO sicoob (num_estabelecimento, data_transacao, num_transacao, id_venda, bandeira, forma_pagamento, plano_venda, parcela, total_parcela, num_autorizacao, tipo_cartao, num_cartao, num_terminal, tipo_captura, indicador_cred_deb, indicador_cancelamento_venda, num_resumo_venda, data_prevista_liquidacao, seu_num, num_ordem_pagamento, estatus, valor_parcela_bruto, desconto_parcela, valor_parcela_liquido, total_plano_venda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Verificar se a preparação da consulta foi bem-sucedida
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }


    // Ler cada linha do CSV
    while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
        // Assumindo que a ordem das colunas no CSV é a mesma da tabela
        $num_estabelecimento = $data[0];
        $data_trasacao = $data[1];
        $num_transacao = $data[2];
        $id_venda = $data[3];
        $bandeira = $data[4];
        $forma_pagamento = $data[5];
        $plano_venda = $data[6];
        $parcela = $data[7];
        $total_parcela = $data[8];
        $num_autorizacao = $data[9];
        $tipo_cartao = $data[10];
        $num_cartao = $data[11];
        $num_terminal = $data[12];
        $tipo_captura = $data[13];
        $indicador_cred_deb = $data[14];
        $indicador_cancelamento_venda = $data[15];
        $num_resumo_venda = $data[16];
        $data_prevista_liquidacao = $data[17];
        $seu_num = $data[18];
        $num_ordem_pagamento = $data[19];
        $estatus = $data[20];
        $valor_parcela_bruto = $data[21];
        $desconto_parcela = $data[22];
        $valor_parcela_liquido = $data[23];
        $total_plano_venda = $data[24];
        

        $stmt->bind_param("sssssssiisssississsssssss", $num_estabelecimento, $data_trasacao, $num_transacao, $id_venda, $bandeira, $forma_pagamento, $plano_venda, $parcela, $total_parcela, $num_autorizacao, $tipo_cartao, $num_cartao, $num_terminal, $tipo_captura, $indicador_cred_deb, $indicador_cancelamento_venda, $num_resumo_venda, $data_prevista_liquidacao, $seu_num, $num_ordem_pagamento, $estatus, $valor_parcela_bruto, $desconto_parcela, $valor_parcela_liquido, $total_plano_venda);
        
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