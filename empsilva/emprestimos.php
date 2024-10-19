<?php
require_once '../session_check.php';
require_once 'db.php';

if($_SESSION['produto'] !== 1 && $_SESSION['produto'] !== 0){
    header('Location: ../valida_produto.php');
}

$database1 = new DatabaseEmpSilva();
$conn = $database1->getConnection();

$query = "SELECT e.id, c.nome AS cliente_nome, e.valor_total, e.duracao_meses, e.valor_aberto
          FROM emprestimos e
          JOIN clientes c ON e.id_cliente = c.id WHERE e.estado = 1 AND c.ativo = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryClientes = "SELECT id, nome FROM clientes WHERE ativo = 1 AND emprestimo_ativo = 0";
$stmtClientes = $conn->prepare($queryClientes);
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos</title>
    <link rel="stylesheet" href="style1.css">
    <?php include '../bootstrap.php'; ?>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="modal fade" id="newLoanModal" tabindex="-1" aria-labelledby="newLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newLoanModalLabel">Cadastrar Novo Empréstimo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newLoanForm" enctype="multipart/form-data">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="clientId" class="form-label me-2">Cliente</label>
                            <select class="form-select" id="clientId" required style="flex: 1;">
                                <option value="">Selecione um cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-primary btn-circle ms-2" onclick="window.location.href='clientes.php'">+</button>
                        </div>
                        <div class="mb-3">
                            <label for="loanAmount" class="form-label">Valor do Empréstimo</label>
                            <input type="number" class="form-control" id="loanAmount" step="50.00" required>
                        </div>
                        <div class="mb-3">
                            <label for="loanDuration" class="form-label">Duração (meses)</label>
                            <input type="number" class="form-control" id="loanDuration" min="1" max="12" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewLoan()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <button type="button" class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#newLoanModal">Cadastrar Novo Empréstimo</button>
        <table class="table table-bordered table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Cliente</th>
                    <th>Valor Total</th>
                    <th>Duração (meses)</th>
                    <th>Valor Aberto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprestimos as $emprestimo): ?>
                    <tr>
                        <td><?php echo $emprestimo['id']; ?></td>
                        <td><?php echo $emprestimo['cliente_nome']; ?></td>
                        <td><?php echo $emprestimo['valor_total']; ?></td>
                        <td><?php echo $emprestimo['duracao_meses']; ?></td>
                        <td><?php echo $emprestimo['valor_aberto']; ?></td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#loanModal<?php echo $emprestimo['id']; ?>">Mostrar</button>
                        </td>
                    </tr>
                    <div class="modal fade" id="loanModal<?php echo $emprestimo['id']; ?>" tabindex="-1" aria-labelledby="loanModalLabel<?php echo $emprestimo['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="loanModalLabel<?php echo $emprestimo['id']; ?>">Dados do Empréstimo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="client-name<?php echo $emprestimo['id']; ?>" class="form-label">Nome do Cliente</label>
                                            <input type="text" class="form-control" id="client-name<?php echo $emprestimo['id']; ?>" value="<?php echo $emprestimo['cliente_nome']; ?>" required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit-loan-amount<?php echo $emprestimo['id']; ?>" class="form-label">Valor do Empréstimo</label>
                                            <input type="number" class="form-control" id="edit-loan-amount<?php echo $emprestimo['id']; ?>" value="<?php echo $emprestimo['valor_total']; ?>" required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit-loan-duration<?php echo $emprestimo['id']; ?>" class="form-label">Duração (meses)</label>
                                            <input type="number" class="form-control" id="edit-loan-duration<?php echo $emprestimo['id']; ?>" value="<?php echo $emprestimo['duracao_meses']; ?>" required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="valor-aberto<?php echo $emprestimo['id']; ?>" class="form-label">Valor em Aberto</label>
                                            <input type="number" class="form-control" id="valor-aberto<?php echo $emprestimo['id']; ?>" value="<?php echo $emprestimo['valor_aberto']; ?>" required disabled>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="showInstallments(<?php echo $emprestimo['id'] ?>,<?php echo $emprestimo['duracao_meses']; ?>)">Ver Parcelas</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="reloadPage()" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="installmentsModal" tabindex="-1" aria-labelledby="installmentsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="installmentsModalLabel">Parcelas em Aberto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="installmentsTableContainer"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripta.js"></script>
</body>
</html>