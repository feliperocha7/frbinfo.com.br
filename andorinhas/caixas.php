<?php
require_once '../session_check.php'; // Certifique-se de que o caminho está correto
require_once 'db.php'; // Para a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados do formulário
    $loja = $_SESSION['loja'];
    $caixa = $_POST['caixa']; // Você pode mudar isso conforme necessário
    $abertura = $_POST['abertura'];
    $dinheiro = (!$_POST['dinheiro']) ? 0 : $_POST['dinheiro'];
    $debito = (!$_POST['debito']) ? 0 : $_POST['debito'];
    $credito = (!$_POST['credito']) ? 0 : $_POST['credito'];
    $cheque = (!$_POST['cheque']) ? 0 : $_POST['cheque'];
    $deposito = (!$_POST['deposito']) ? 0 : $_POST['deposito'];
    $crediario = (!$_POST['crediario']) ? 0 : $_POST['crediario'];
    $sispumi = (!$_POST['sispumi']) ? 0 : $_POST['sispumi'];
    $gremio = (!$_POST['gremio']) ? 0 : $_POST['gremio'];
    $seicon = (!$_POST['seicon']) ? 0 : $_POST['seicon'];
    $moeda_local = (!$_POST['moeda_local']) ? 0 : $_POST['moeda_local'];
    $pix = (!$_POST['pix']) ? 0 : $_POST['pix'];
    $troco = $_POST['troco'];
    $total_dia = $_POST['total_dia'];
    $operador = $_SESSION['user'];
    $dia = date('Y-m-d');
    $id_usuario = $_SESSION['user_id']; // Você pode mudar isso conforme necessário

    // Crie uma instância da classe de conexão
    $database = new DatabaseAndorinhas();
    $conn = $database->getConnection();

    // Prepare e execute a inserção
    try {
        // Converta as descrições e valores para JSON
        $descricoes = json_encode($_POST['descricao']);
        $valores = json_encode($_POST['valor']);

        // Chame a procedure
        $stmt = $conn->prepare("CALL InserirCaixaEDespesas(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$loja, $caixa, $abertura, $dinheiro, $debito, $credito, $crediario,
                         $cheque, $sispumi, $gremio, $seicon, $moeda_local, $pix, 
                         $deposito, $troco, $operador, $dia, $id_usuario, $descricoes, $valores]);

        echo "Dados inseridos com sucesso!";

        $stmt->close();
        echo '<meta http-equiv="refresh" content="3">';

        } catch (PDOException $e) {
        echo "<script>alert('Erro ao inserir dados: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <title>Caixas</title>
    <style>
        /* Seu CSS aqui */
        table {
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
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    <div class="container">
        <div class="caixas">
            <form method="post">
                <table id="despesasTable">
                    <tr>
                        <th class="col1">Caixa</th>
                        <td class="col2"><input class="invisivel" type="text" name="caixa"  value="1" required></td>
                    </tr>
                    <tr>
                        <th class="col1">Despesa:</th>
                        <th class="col2"></th>
                    </tr>
                    <tr>
                        <td class="col1"><input class="invisivel" type="text" value="" name="descricao[]" ></td>
                        <td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" value="0"name="valor[]" >
                            <button class="add-btn" id="addButton" title="Adicionar nova despesa" type="button" onclick="addDespesa(event)">+</button>
                        </td>
                    </tr>
                    <tr>
                        <th class="th col1">Abertura:</th>
                        <td class="col2"><input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="abertura" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Dinheiro:</td>
                        <td class="col2"><input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="dinheiro" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Débito:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="debito" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Crédito:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="credito" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Cheque:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="cheque" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Pix CNPJ:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="deposito" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Crediário:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="crediario" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Sispumi:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="sispumi" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Grêmio:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="gremio" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Seicon:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="seicon" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Moeda Local:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="moeda_local" value="0" required></td>
                    </tr>
                    <tr>
                        <td class="col1">Pix:</td><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="pix" value="0" required></td>
                    </tr>
                    <tr>
                        <th class="th col1">Troco:</th><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="troco" required></td>
                    </tr>
                    <tr>
                        <th class="col1">Total do dia:</th><td class="col2">
                            <input class="invisivel" type="number" step="0.01" min="0" placeholder="0.00" name="total_dia" required></td>
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
