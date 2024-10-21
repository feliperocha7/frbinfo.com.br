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

$sql = "SELECT id, dia, cfc, cd, descricao, comp, valor, local_pgto, cp, pgto FROM pagamentos";
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
        .linha-pagamento-nao-realizado {
            background-color: #f8d7da; /* Cor de fundo para pagamento não realizado */
        }
        .linha-pagamento-nao-realizado:hover {
            background-color: #f8d7eb; /* Cor de fundo para pagamento não realizado */
        }
        .linha-pagamento-realizado {
            background-color: #d4edda; /* Cor de fundo para pagamento realizado */
        }
        .f{
            height: 1.3rem;
        }
    </style>
    <title>Meus Pagamentos</title>
</head>
<body>
    <?php
        include 'navbar.php';
    ?>
    <div class="container shadow-lg p-3 mb-5 bg-body rounded table-responsive">
        <div class="form-pagamentos">
        <h2 class="mb-4">Formulário de Pagamento</h2>

        <form action="processa_formulario.php" method="POST">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Dia</th>
                        <th scope="col">CFC</th>
                        <th scope="col">CD</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Comp</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Local Pgto</th>
                        <th scope="col">CP</th>
                        <th scope="col">Pago</th>
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
                                <input type="number" class="form-control" id="cfc" name="cfc" max="999" min="0" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="number" class="form-control" id="cd" name="cd" max="999" min="0" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-20 f">
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex.: Fornecedor" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-20 f">
                                <input type="number" class="form-control" id="comp" name="comp" max="12" min="1" required>
                            </div>
                        </td>
                        <td>
                            <div class="input-group mb-3 f">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" aria-label="Amount (to the nearest dollar)" id="valor" name="valor" step='0.01' required>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="text" class="form-control" id="local_pgto" name="local_pgto" placeholder="Ex.: Itaú" required>
                            </div>
                        </td>
                        <td>
                            <div class="col-15 f">
                                <input type="number" class="form-control" id="cp" name="cp" max="100" min="0" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="checkbox" class="form-check-input" id="pgto" name="pgto">
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
                <div class="tabela-pagamentos">
                <?php
                    if ($stmt->rowCount() > 0) {
                        // Saída dos dados de cada linha
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $classe = $row['pgto'] == "0" ? 'linha-pagamento-nao-realizado' : 'linha-pagamento-realizado';
                            echo "<tr class='$classe'>";
                            echo "<td>" . htmlspecialchars($row['dia']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cfc']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cd']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['comp']) . "</td>";
                            echo "<td>R$" . htmlspecialchars($row['valor']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['local_pgto']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cp']) . "</td>";
                            echo "<td><div class='form-group form-check'>" . ($row['pgto'] ? 'Sim' : 'Não') . "</div></td>"; // Exibe 'Sim' ou 'Não' para o checkbox
                            echo "<td>
                                    <div class='btn-group btn-group-sm' role='group' aria-label='Basic mixed styles example'>
                                        <button type='button' class='btn btn-danger' onclick='excluirPagamento(" . htmlspecialchars($row['id']) . ")'>Excluir</button>
                                        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#Modaleditar" . htmlspecialchars($row['id']) . "'>Editar</button>
                                    </div>
                                </td>";
                            echo "</tr>";
                            ?>
                            <div class="modal fade bd-example-modal-xl" id="Modaleditar<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="ModaleditarlLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-xl mod'al-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="Modaleditar">Detalhes do Pagamento</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label for="editdia<?php echo $row['id']; ?>" class="col-form-label">Dia:</label>
                                                            <input type="text" class="form-control" id="editdia<?php echo $row['id']; ?>" value="<?php echo $row['dia']; ?>" data-original="<?php echo $row['dia']; ?>">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="editcfc<?php echo $row['id']; ?>" class="col-form-label">CFC:</label>
                                                            <input type="text" class="form-control" id="editcfc<?php echo $row['id']; ?>" value="<?php echo $row['cfc']; ?>" data-original="<?php echo $row['cfc']; ?>">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="editcd<?php echo $row['id']; ?>" class="col-form-label">CD:</label>
                                                            <input type="text" class="form-control" id="editcd<?php echo $row['id']; ?>" value="<?php echo $row['cd']; ?>" data-original="<?php echo $row['cd']; ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="editdescricao<?php echo $row['id']; ?>" class="col-form-label">Descrição:</label>
                                                            <input type="text" class="form-control" id="editdescricao<?php echo $row['id']; ?>" value="<?php echo $row['descricao']; ?>" data-original="<?php echo $row['descricao']; ?>">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="editcomp<?php echo $row['id']; ?>" class="col-form-label">COMP:</label>
                                                            <input type="text" class="form-control" id="editcomp<?php echo $row['id']; ?>" value="<?php echo $row['comp']; ?>" data-original="<?php echo $row['comp']; ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="editvalor<?php echo $row['id']; ?>" class="col-form-label">Valor:</label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text">R$</span>
                                                                <input type="text" class="form-control" id="editvalor<?php echo $row['id']; ?>" value="<?php echo $row['valor']; ?>" data-original="<?php echo $row['valor']; ?>" step='0.01'>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="editlocalpgto<?php echo $row['id']; ?>" class="col-form-label">Local Pgto:</label>
                                                            <input type="text" class="form-control" id="editlocalpgto<?php echo $row['id']; ?>" value="<?php echo $row['local_pgto']; ?>" data-original="<?php echo $row['local_pgto']; ?>">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="editcp<?php echo $row['id']; ?>" class="col-form-label">CP:</label>
                                                            <input type="text" class="form-control" id="editcp<?php echo $row['id']; ?>" value="<?php echo $row['cp']; ?>" data-original="<?php echo $row['cp']; ?>">
                                                        </div>
                                                        <div class="col-md-1 form-check">
                                                            <label for="editpago<?php echo $row['id']; ?>" class="col-form-label">Pago:</label>
                                                            <!-- Checkbox com valor "1" quando marcado -->
                                                            <input type="checkbox" class="form-check" id="editpago<?php echo $row['id']; ?>" name="editpago<?php echo $row['id']; ?>" value="1" <?php if ($row['pgto']){ echo 'checked';}else{ echo '';}; ?> data-original="<?php echo $row['pgto'] ? 1 : 0; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-primary" onclick="salvarPagamento(<?php echo $row['id']; ?>)">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='10'>Nenhum pagamento encontrado.</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>

        
    </div>
    
    <?php include '../bootstrap_js.php'; ?>
    <script src="script.js"></script>
</body>
</html>
