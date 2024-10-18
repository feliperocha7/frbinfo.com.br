<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivo CSV</title>
</head>
<body>
    <h1>Carregar Arquivo CSV</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="csvFile" accept=".csv" required>
        <input type="submit" value="Carregar">
    </form>

    <?php
    include 'db_connection.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
        if ($_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
            $csvFile = $_FILES['csvFile']['tmp_name'];

            if (($handle = fopen($csvFile, 'r')) !== FALSE) {
                $firstRow = fgetcsv($handle, 1000, ';');
                $firstCell = trim(preg_replace('/\x{FEFF}/u', '', $firstRow[0]));

                if ($firstCell === 'DOCUMENTO') {
                    $detectedTable = 'stone';
                } elseif ($firstCell === 'Relatório de vendas') {
                    $detectedTable = 'sicoob';
                    fgetcsv($handle, 1000, ';'); // Ignora a segunda linha
                } else {
                    echo "Estrutura do arquivo CSV não reconhecida. Primeira linha: " . htmlspecialchars($firstCell);
                    fclose($handle);
                    return;
                }

                // Obter a última data inserida
                if ($detectedTable === 'stone') {
                    $resultStone = $conn->query("SELECT MAX(data_venda) as last_date FROM stone");
                    $lastDateStone = $resultStone->fetch_assoc()['last_date'];
                    echo "<script>console.log(" . $lastDateStone . ")</script>";
                } elseif ($detectedTable === 'sicoob') {
                    $resultSicoob = $conn->query("SELECT MAX(data_transacao) as last_date FROM sicoob");
                    $lastDateSicoob = $resultSicoob->fetch_assoc()['last_date'];
                    echo "<script>console.log(" . $lastDateSicoob . ")</script>";
                }

                if ($detectedTable === 'stone') {
                    $stmtStone = $conn->prepare("INSERT INTO stone (documento, stonecode, nome_fantasia, categoria, data_venda, data_vencimento, data_vencimento_original, bandeira, produto, stone_id, qtd_parcelas, num_parcelas, valor_bruto, valor_liquido, desconto_mdr, desconto_antecipacao, num_cartao, ultimo_status, data_ultimo_status, chave_externa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                } elseif ($detectedTable === 'sicoob') {
                    $stmtSicoob = $conn->prepare("INSERT INTO sicoob (num_estabelecimento, data_transacao, num_transacao, id_venda, bandeira, forma_pagamento, plano_venda, parcela, total_parcela, num_autorizacao, tipo_cartao, num_cartao, num_terminal, tipo_captura, indicador_cred_deb, indicador_cancelamento_venda, num_resumo_venda, data_prevista_liquidacao, seu_num, num_ordem_pagamento, estatus, valor_parcela_bruto, desconto_parcela, valor_parcela_liquido, total_plano_venda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                }

                if ($detectedTable === 'sicoob') {
                    fgetcsv($handle, 1000, ';'); // Ignora a terceira linha
                }

                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    if ($detectedTable === 'stone') {
                        if (count($data) < 20) continue; // Verifique o número de colunas

                        // Ignora se a categoria for 'Balanceamento de Saldo'
                        if (trim($data[3]) === 'Balanceamento de Saldo') {
                            continue;
                        }

                        // $dataString = '30-09-2024 19:14';
                        // $dataTime = new DateTime($dataString);
                        // echo "TESTEEEEE " . $dataTime->format('Y/m/d H:i');
                        // if($dataTime === false){
                        //     echo "ERRO AQUIII";
                        // }
                        
                        $parte = substr($data[4], 0, 2);
                        // Usar DateTime::createFromFormat para garantir o formato correto
                        if ($parte === '30') {
                            //$data_venda = substr($data[4], 0, 8);
                            continue;
                        }else{
                            $data_venda = $data[4]; //new DateTime($data[4]); // Supondo que data_venda é a quinta coluna
                            //continue;
                        }
                        //echo " data da venda !!!!!!!!!: " . $data_venda->format('Y/m/d H:i');
                        //echo " ULTIMAAAAA: " . $lastDateStone;
                        

                        if ($data_venda === false) {
                            echo "Erro ao analisar a data de venda: " . htmlspecialchars($data[4]);
                            continue;
                        }

                        if ($lastDateStone === null || $data_venda > $lastDateStone) {
                            $stmtStone->bind_param("ssssssssssiissssssss", ...$data);
                            if (!$stmtStone->execute()) {
                                echo "Erro ao inserir dados na tabela stone: " . $stmtStone->error . "<br>";
                            }
                        }
                    } elseif ($detectedTable === 'sicoob') {
                        if (count($data) < 25) continue; // Verifique o número de colunas
                        // Usar DateTime::createFromFormat para garantir o formato correto
                        $data_transacao = DateTime::createFromFormat('d/m/Y H:i:s', $data[1]); // Supondo que data_transacao é a segunda coluna

                        if ($data_transacao === false) {
                            echo "Erro ao analisar a data de transação: " . htmlspecialchars($data[1]);
                            continue;
                        }

                        if ($lastDateSicoob === null || $data_transacao > new DateTime($lastDateSicoob)) {
                            $stmtSicoob->bind_param("sssssssiisssississsssssss", ...$data);
                            if (!$stmtSicoob->execute()) {
                                echo "Erro ao inserir dados na tabela sicoob: " . $stmtSicoob->error . "<br>";
                            }
                        }
                    }
                }

                if (isset($stmtStone)) $stmtStone->close();
                if (isset($stmtSicoob)) $stmtSicoob->close();
                fclose($handle);
                echo "Dados importados com sucesso para a tabela: " . htmlspecialchars($detectedTable);
            } else {
                echo "Não foi possível abrir o arquivo CSV.";
            }
        } else {
            echo "Erro ao carregar o arquivo: " . $_FILES['csvFile']['error'];
        }

        $conn->close();
    }
    ?>
</body>
</html>
