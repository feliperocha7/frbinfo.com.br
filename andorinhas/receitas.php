<?php
require_once '../session_check.php';
require_once 'db.php';

if($_SESSION['produto'] !== 2 && $_SESSION['produto'] !== 0){
    header('Location: ../valida_produto.php');
}else if($_SESSION['perfil'] == 'operador'){
    header('Location: /frbinfo.com.br/andorinhas/caixas.php');
}

$database = new DatabaseAndorinhas();
$conn = $database->getConnection();

$sql = "SELECT dia, comp, descricao, valor, banco, cod FROM receitas";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <?php include '../bootstrap.php'; ?>
    <style>
        .table {
            border-radius: 0.5rem; /* Ajuste o valor conforme necessário */
            overflow: hidden; /* Para garantir que as bordas arredondadas sejam aplicadas */
            text-align: center;
            align-items: center;
        }
        .table th, .table tr{
            padding: 0.3rem;
        }
        tr:hover{
            background-color: #f8f9fa;
        }
        .f{
            height: 1.3rem;
        }
    </style>
    <title>Receitas</title>
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    <div class="container shadow-lg p-3 mb-5 bg-body rounded">
        <div class="form-pagamentos">
        <h2 class="mb-4">Formulário de Receitas</h2>

        <form action="processa_formulario_receitas.php" method="POST">
            <table class="table  table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Dia</th>
                        <th scope="col">Comp.</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Banco</th>
                        <th scope="col">COD</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="col-15 f">
                                <input type="number" class="form-control" id="dia" min="1" max="31" name="dia" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="number" class="form-control" id="comp" min="1" max="12" name="comp" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-20 f">
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex.: Fornecedor" required>
                            </div>
                        </td>
                        <td>
                            <div class="input-group mb-3 f">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" id="valor" name="valor" required>
                                <span class="input-group-text">.00</span>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="text" class="form-control" id="banco" name="banco" placeholder="Ex.: Itaú" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="number" class="form-control" id="cod" name="cod" max="999" min="0" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="submit" class="btn btn-primary btn-sm" value="Salvar">
                            </div>
                        </td>
                    </tr>
                </form>     
                </div>
                <div class="tabela-receitas">
                    <?php
                        if ($stmt->rowCount() > 0) {
                            // Saída dos dados de cada linha
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr'>";
                                echo "<td>" . htmlspecialchars($row['dia']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['comp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                                echo "<td>R$" . htmlspecialchars($row['valor']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['banco']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['cod']) . "</td>";
                                echo '<td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                                            <button type="button" class="btn btn-danger">Excluir</button>
                                            <button type="button" class="btn btn-success">Editar</button>
                                        </div>
                                      </td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Nenhuma receita encontrado.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
