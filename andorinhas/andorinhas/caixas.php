<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loja = $_POST['loja'];
    $caixa = 1;
    $abertura = $_POST['abertura'];
    $dinheiro = $_POST['dinheiro'];
    $debito = $_POST['debito'];
    $credito = $_POST['credito'];
    $cheque = $_POST['cheque'];
    $deposito = $_POST['deposito'];
    $crediario = $_POST['crediario'];
    $sispumi = $_POST['sispumi'];
    $gremio = $_POST['gremio'];
    $seicon = $_POST['seicon'];
    $moeda_local = $_POST['moeda_local'];
    $pix = $_POST['pix'];
    $troco = $_POST['troco'];
    $total_dia = $_POST['total_dia'];
    $operador = $_POST['operador'];
    $dia = date('Y-m-d');
    $id_usuario = 1;

    $query = $conn->prepare("INSERT INTO caixas (id_loja, caixa, abertura, dinheiro, cartao_debito, cartao_credito, crediario,
        cheques, sispumi, gremio, seicon, moeda_local, pix, deposito, troco_final, operador, data, id_usuario) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    if ($query === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Associar os parâmetros
    $query->bind_param("iisssssssssssssssi", $id_loja, $caixa, $abertura, $dinheiro, $debito, $credito, $crediario,
    $cheque, $sispumi, $gremio, $seicon, $moeda_local, $pix, $deposito, $troco, $operador, $dia, $id_usuario);

    // Executar a inserção
    if ($query->execute()) {
        echo "Dados inseridos com sucesso! ID da nova entrada: " . $conn->insert_id;
    } else {
        echo "Erro ao inserir dados: " . $query->error;
    }
    
    $query->close();




    // Consulta SQL
    $sql = "SELECT id FROM caixas WHERE operador = ? AND data = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }
    // Bind do parâmetro
    $stmt->bind_param("ss", $operador, $dia); // 's' indica que o parâmetro é uma string
    // Executar a consulta
    $stmt->execute();
    // Recuperar o resultado
    $result = $stmt->get_result();
    // Verifica se houve resultados
    if ($result && $result->num_rows > 0) {
        // Armazenar o resultado em uma variável
        $dados = $result->fetch_assoc();
        // Exibir os dados
        $idCaixa = $dados["id"];
    } else {
        echo "Nenhum resultado encontrado ou erro na consulta: " . $conn->error;
    }

    // Fechar a conexão
    $stmt->close();


     // Preparando a inserção das despesas
     $stmt1 = $conn->prepare("INSERT INTO despesas (id_caixa, descricao, valor, id_usuario) VALUES (?, ?, ?, ?)");

     // Para as linhas criadas dinamicamente
     foreach ($_POST['descricao'] as $index => $descricao) {
         $valor = $_POST['valor'][$index];
         $stmt1->bind_param("ssis", $idCaixa, $descricao, $valor, $operador);
         $stmt1->execute();
     }

     echo "Dados inseridos com sucesso!";
     $stmt1->close();
}

//$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>Caixas</title>
    <style>
        /* Seu CSS aqui */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 3px solid;
            position: relative;
        }
        tr, th, td {
            border: 1px solid black;
            position: relative;
        }
        th {
            background-color: #7c7c7c;
            color: white;
        }
        .th {
            text-align: left;
            background-color: #c2c2c2;
        }
        .col1 {
            width: 250px;
            padding: 0.5%;
        }
        .col2 {
            width: 150px;
            position: relative; /* Necessário para o botão flutuar */
        }
        .invisivel {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
            padding: 0;
            font-size: 16px;
            outline: none;
        }
        .btn {
            width: 150px;
            border-radius: 20px;
        }
        .add-btn {
            position: absolute;
            right: -20px; /* Ajuste para que fique flutuando dentro e fora */
            top: 50%; /* Centraliza verticalmente */
            transform: translateY(-50%); /* Ajusta para ficar no centro */
            width: 40px; /* Largura do botão */
            height: 40px; /* Altura do botão */
            border-radius: 50%; /* Botão redondo */
            background-color: #007BFF; /* Cor de fundo */
            color: white; /* Cor do texto */
            border: none; /* Remove a borda padrão */
            font-size: 24px; /* Tamanho do símbolo */
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.5); /* Sombra para efeito 3D */
            transition: all 0.2s; /* Transição suave */
        }
        .add-btn:hover {
            background-color: #0056b3; /* Cor ao passar o mouse */
        }
        .add-btn:active {
            transform: translateY(1px); /* Efeito de depressão ao clicar */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="caixas">
            <form method="post">
                <table id="despesasTable">
                    <tr>
                        <th class="col1">Loja</th>
                        <td class="col2"><input class="invisivel" type="text" name="loja" required></td>
                    </tr>
                    <tr>
                        <th class="col1">Despesa:</th>
                        <th class="col2"></th>
                    </tr>
                    <tr>
                        <td class="col1"><input class="invisivel" type="text" name="descricao[]" required></td>
                        <td class="col2">
                            <input class="invisivel" type="text" name="valor[]" required>
                            <button class="add-btn" id="addButton" title="Adicionar nova despesa" type="button" onclick="addDespesa(event)">+</button>
                        </td>
                    </tr>
                    <tr>
                        <th class="th col1">Abertura:</th>
                        <td class="col2"><input class="invisivel" type="text" name="abertura" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Dinheiro:</td><td class="col2"><input class="invisivel" type="text" name="dinheiro" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Débito:</td><td class="col2"><input class="invisivel" type="text" name="debito" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Crédito:</td><td class="col2"><input class="invisivel" type="text" name="credito" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Cheque:</td><td class="col2"><input class="invisivel" type="text" name="cheque" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Depósito:</td><td class="col2"><input class="invisivel" type="text" name="deposito" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Crediário:</td><td class="col2"><input class="invisivel" type="text" name="crediario" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Sispumi:</td><td class="col2"><input class="invisivel" type="text" name="sispumi" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Grêmio:</td><td class="col2"><input class="invisivel" type="text" name="gremio" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Seicon:</td><td class="col2"><input class="invisivel" type="text" name="seicon" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Moeda Local:</td><td class="col2"><input class="invisivel" type="text" name="moeda_local" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Pix:</td><td class="col2"><input class="invisivel" type="text" name="pix" required></td>
                    </tr>
                    <tr>
                        <th class="th col1">Troco:</th><td class="col2"><input class="invisivel" type="text" name="troco" required></td>
                    </tr>
                    <tr>
                        <th class="col1">Total do dia:</th><td class="col2"><input class="invisivel" type="text" name="total_dia" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Operador:</td><td><input class="invisivel" type="text" name="operador" required></td>
                    </tr>
                    <tr>
                        <td class="col1" colspan="2"><input class="btn" type="submit" value="Enviar"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <script>
        function addDespesa(event) {
            event.preventDefault(); // Impede o envio do formulário
            const table = document.getElementById('despesasTable');
            const newRow = table.insertRow(3); // Insere a nova linha após a terceira linha

            const newCellDespesa = newRow.insertCell(0);
            const newCellValor = newRow.insertCell(1);

            newCellDespesa.innerHTML = '<input class="invisivel" type="text" name="descricao[]" required>';
            newCellValor.innerHTML = '<input class="invisivel" type="text" name="valor[]" required>';
        }
    </script>
</body>
</html>
