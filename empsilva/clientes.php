<?php
require_once '../session_check.php';
require_once 'db.php';

if($_SESSION['produto'] !== 1 && $_SESSION['produto'] !== 0){
    header('Location: ../valida_produto.php');
}

// Conexão com o banco de dados
$database = new DatabaseEmpSilva();
$conn = $database->getConnection();

// Consulta para obter todos os clientes
$query = "SELECT * FROM clientes";
$stmt = $conn->prepare($query);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="style1.css"> <!-- Usando o estilo padrão -->
    <?php include '../bootstrap.php'; ?>
</head>
<body>
    <?php include 'navbar.php'; // Navbar no início do body ?>
    <div class="container shadow-lg p-3 mb-5 bg-body rounded">
        <!-- Modal Cadastro Novo Cliente -->
        <div class="modal fade" id="newClientModal" tabindex="-1" aria-labelledby="newClientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newClientModalLabel">Cadastrar Novo Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newClientForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="clientName" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="clientName" required>
                            </div>
                            <div class="mb-3">
                                <label for="new-client-cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="new-client-cpf" >
                            </div>
                            <div class="mb-3">
                                <label for="new-client-indicator" class="form-label">Indicação</label>
                                <input type="text" class="form-control" id="new-client-indicator" >
                            </div>
                            <div class="mb-3">
                                <label for="clientDocument" class="form-label">Documento</label>
                                <input type="file" class="form-control" id="clientDocument" accept="image/*,application/pdf" >
                            </div>
                            <div class="mb-3">
                                <label for="clientResidence" class="form-label">Comprovante de Residência</label>
                                <input type="file" class="form-control" id="clientResidence" accept="image/*,application/pdf" >
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="saveNewClient()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="container shadow-lg p-3 mb-5 bg-body rounded table-responsive" >
            <button type="button" class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#newClientModal">
                Cadastrar Novo Cliente
            </button>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Documento</th>
                        <th>Comprovante de Residência</th>
                        <th>Idicação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['id']; ?></td>
                            <td><?php echo $cliente['nome']; ?></td>
                            <td><?php echo $cliente['cpf']; ?></td>
                            <td>
                                <?php if (!empty($cliente['documento'])): ?>
                                    <a href="uploads/documentos/<?php echo $cliente['documento']; ?>" target="_blank">Ver Documento</a>
                                <?php else: ?>
                                    Nenhum documento
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($cliente['comprovante_residencia'])): ?>
                                    <a href="uploads/comprovantes_residencia/<?php echo $cliente['comprovante_residencia']; ?>" target="_blank">Ver Comprovante</a>
                                <?php else: ?>
                                    Nenhum comprovante
                                <?php endif; ?>
                            </td>
                            <td><?php echo $cliente['indicacao']; ?></td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#clientModal<?php echo $cliente['id']; ?>">Mostrar</button>
                            </td>
                        </tr>
                        <!-- Modal de Exibição dos Dados do Cliente -->
                        <div class="modal fade" id="clientModal<?php echo $cliente['id']; ?>" tabindex="-1" aria-labelledby="clientModalLabel<?php echo $cliente['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="clientModalLabel<?php echo $cliente['id']; ?>">Dados do Cliente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="mb-3">
                                                <label for="edit-client-name<?php echo $cliente['id']; ?>" class="form-label">Nome</label>
                                                <input type="text" class="form-control" id="edit-client-name<?php echo $cliente['id']; ?>" value="<?php echo $cliente['nome']; ?>" required disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit-client-cpf<?php echo $cliente['id']; ?>" class="form-label">CPF</label>
                                                <input type="text" class="form-control" id="edit-client-cpf<?php echo $cliente['id']; ?>" value="<?php echo $cliente['cpf']; ?>" required disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit-client-document<?php echo $cliente['id']; ?>" class="form-label">Documento</label>
                                                <input type="file" class="form-control" id="edit-client-document<?php echo $cliente['id']; ?>" accept="image/*,application/pdf" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit-client-residence<?php echo $cliente['id']; ?>" class="form-label">Comprovante de Residência</label>
                                                <input type="file" class="form-control" id="edit-client-residence<?php echo $cliente['id']; ?>" accept="image/*,application/pdf" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit-client-indicator<?php echo $cliente['id']; ?>" class="form-label">Indicação</label>
                                                <input type="text" class="form-control" id="edit-client-indicator<?php echo $cliente['id']; ?>" value="<?php echo $cliente['indicacao']; ?>" required disabled>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="enableEditClient(<?php echo $cliente['id']; ?>)">Editar</button>
                                        <button type="button" class="btn btn-primary" onclick="saveClient(<?php echo $cliente['id']; ?>)">Salvar</button>
                                        <button type="button" class="btn btn-danger" onclick="deleteClient(<?php echo $cliente['id']; ?>)">Excluir</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripta.js"></script> <!-- Importando seu scripts.js -->
    <?php include '../bootstrap_js.php'; ?>
</body>
</html>