<?php
// Conexão com o banco de dados
$host = 'localhost';
$db = 'andorinhas';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loja = $_POST['loja']; // Adapte conforme o nome do campo
    $operador = $_POST['operador']; // Adapte conforme o nome do campo

    // Preparando a inserção das despesas
    $stmt = $conn->prepare("INSERT INTO despesas (loja, despesa, valor, operador) VALUES (?, ?, ?, ?)");

    // Para as linhas criadas dinamicamente
    foreach ($_POST['despesa'] as $index => $despesa) {
        $valor = $_POST['valor'][$index];
        $stmt->bind_param("ssis", $loja, $despesa, $valor, $operador);
        $stmt->execute();
    }

    echo "Dados inseridos com sucesso!";
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>Caixas</title>
    <style>
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
            position: relative;
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
                        <td class="col1"><input class="invisivel" type="text" name="despesa[]" required></td>
                        <td class="col2">
                            <input class="invisivel" type="text" name="valor[]" required>
                            <button class="add-btn" id="addButton" title="Adicionar nova despesa" onclick="addDespesa(event)">+</button>
                        </td>
                    </tr>
                    <tr>
                        <th class="th col1">Operador:</th>
                        <td class="col2"><input class="invisivel" type="text" name="operador" required></td>
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

            newCellDespesa.innerHTML = '<input class="invisivel" type="text" name="despesa[]" required>';
            newCellValor.innerHTML = '<input class="invisivel" type="text" name="valor[]" required>';
        }
    </script>
</body>
</html>
