<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Consulta</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Resultados da Consulta</h1>

    <?php
    // Incluir o arquivo de conexão
    include 'db_connection.php';

    // Definir a data que você deseja consultar
    $dataConsulta = '04/10/2024';

    // Preparar a consulta SQL
    $query = "SELECT m.nome_loja AS LOJA, 
                    CAST(SUM(REPLACE(s.valor_liquido, ',', '.')) AS DECIMAL(10, 2)) AS VALOR_LIQUIDO
                FROM stone as s INNER JOIN maquininhas_stone as m on s.stonecode = m.stonecode 
                WHERE LEFT(s.data_venda, 10) = ? 
                GROUP BY m.nome_loja 
                ORDER BY m.nome_loja ASC";

    // Preparar a declaração
    if ($stmt = $conn->prepare($query)) {
        // Vincular parâmetros
        $stmt->bind_param("s", $dataConsulta);

        // Executar a consulta
        $stmt->execute();

        // Obter resultados
        $result = $stmt->get_result();

        // Verificar se há resultados
        if ($result->num_rows > 0) {
            // Iniciar a tabela
            echo '<table>';
            echo '<tr><th>Loja</th><th>Valor Líquido</th></tr>';

            // Exibir os dados em linhas da tabela
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['LOJA']) . '</td>';
                
                // Formatar o valor líquido como moeda
                $valorLiquido = number_format($row['VALOR_LIQUIDO'], 2, ',', '.');
                echo '<td>R$ ' . $valorLiquido . '</td>';
                echo '</tr>';
            }

            // Finalizar a tabela
            echo '</table>';
        } else {
            echo "Nenhum resultado encontrado.";
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        echo "Erro na consulta: " . $conn->error;
    }

    // Fechar a conexão
    $conn->close();
    ?>
</body>
</html>
