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

$sql = "SELECT id, dia, comp, descricao, valor, banco, cod, id_loja FROM receitas";
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
    <div class="container shadow-lg p-3 mb-5 bg-body rounded table-responsive">
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
                        <th scope="col">Loja</th>
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
                                <input type="number" class="form-control" id="loja" name="loja" max="8" min="1" required>
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
                                echo "<td>" . htmlspecialchars($row['id_loja']) . "</td>";
                                echo "<td>
                                    <div class='btn-group btn-group-sm' role='group' aria-label='Basic mixed styles example'>
                                        <button type='button' class='btn btn-danger' onclick='excluirReceita(" . htmlspecialchars($row['id']) . ")'>Excluir</button>
                                        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#ModaleditarReceita" . htmlspecialchars($row['id']) . "'>Editar</button>
                                    </div>
                                </td>";
                                echo "</tr>";
                                ?>
                            <div class="modal fade bd-example-modal-xl" id="ModaleditarReceita<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="ModaleditarReceita<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ModaleditarReceita">Detalhes do Pagamento</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <label for="editdiaR<?php echo $row['id']; ?>" class="col-form-label">Dia:</label>
                                                        <input type="text" class="form-control" id="editdiaR<?php echo $row['id']; ?>" value="<?php echo $row['dia']; ?>">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label for="editcompR<?php echo $row['id']; ?>" class="col-form-label">COMP:</label>
                                                        <input type="text" class="form-control" id="editcompR<?php echo $row['id']; ?>" value="<?php echo $row['comp']; ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="editvalorR<?php echo $row['id']; ?>" class="col-form-label">Valor:</label>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">R$</span>
                                                            <input type="text" class="form-control" id="editvalorR<?php echo $row['id']; ?>" aria-label="Amount (to the nearest dollar)" value="<?php echo $row['valor']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="editbancoR<?php echo $row['id']; ?>" class="col-form-label">Banco:</label>
                                                        <input type="text" class="form-control" id="editbancoR<?php echo $row['id']; ?>" value="<?php echo $row['local_pgto']; ?>">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label for="editcodR<?php echo $row['id']; ?>" class="col-form-label">COD:</label>
                                                        <input type="text" class="form-control" id="editcodR<?php echo $row['id']; ?>" value="<?php echo $row['cp']; ?>">
                                                    </div>  
                                                    <div class="col-md-1">
                                                        <label for="editlojaR<?php echo $row['id']; ?>" class="col-form-label">Loja:</label>
                                                        <input type="text" class="form-control" id="editlojaR<?php echo $row['id']; ?>" value="<?php echo $row['cp']; ?>">
                                                    </div> 
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-primary" onclick="salvarReceita(<?php echo $row['id']; ?>)">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>Nenhuma receita encontrado.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
                        
    <?php include '../bootstrap_js.php'; ?>
</body>
</html>
