<?php
require_once 'session_check.php';

if($_SESSION['produto'] !== 0){
    header('Location: valida_produto.php');
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <?php include 'bootstrap.php'; ?>
    <title>Painel</title>
</head>
<body>
    <?php
        include 'navbar.php';
    ?> 
    <div class="container shadow-lg p-3 mb-5 bg-body rounded">
        
        <div class="container mt-4">
            <div class="btn-painel">
                <div>
                    <!-- Botão para abrir o modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">
                        Cadastrar Novo Usuário
                    </button>
                </div>
                <div>
                    <!-- Botão para abrir o modal de cadastro de empresa -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroEmpresaModal">
                        Cadastrar Nova Empresa
                    </button>
                </div>
                <div>
                    <!-- Botão para listar usuários -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listarUsuariosModal">
                        Listar Usuários
                    </button>
                </div>
                <div>
                    <!-- Botão para listar empresas -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listarEmpresasModal">
                        Listar Empresas
                    </button>
                </div>
            </div>

            <!-- Modal para cadastro de usuário -->
            <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Novo Usuário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="cadastroForm">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nome de Usuário</label>
                                    <input type="text" class="form-control" id="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" id="confirmPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="empresaSelect" class="form-label">Selecione a Empresa</label>
                                    <select class="form-select" id="empresaSelect" required>
                                        <option value="" disabled selected>Escolha uma opção</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="perfil" class="form-label">Selecione o Perfil</label>
                                    <select class="form-select" id="perfil" required>
                                        <option value="" disabled selected>Escolha uma opção</option>
                                        <option value="master">Master</option>
                                        <option value="admin">Admin</option>
                                        <option value="operador">Operador</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" id="submitCadastro">Cadastrar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal para cadastro de empresa -->
            <div class="modal fade" id="cadastroEmpresaModal" tabindex="-1" aria-labelledby="cadastroEmpresaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadastroEmpresaModalLabel">Cadastrar Nova Empresa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="cadastroEmpresaForm">
                                <div class="mb-3">
                                    <label for="empresaNome" class="form-label">Nome da Empresa</label>
                                    <input type="text" class="form-control" id="empresaNome" required>
                                </div>
                                <div class="mb-3">
                                    <label for="empresaCaminho" class="form-label">Caminho da Empresa</label>
                                    <input type="text" class="form-control" id="empresaCaminho" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" id="submitCadastroEmpresa">Cadastrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Modal para listar usuários -->
        <div class="modal fade" id="listarUsuariosModal" tabindex="-1" aria-labelledby="listarUsuariosModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="listarUsuariosModalLabel">Lista de Usuários</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome de Usuário</th>
                                    <th>Perfil</th>
                                    <th>Empresa</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="usuariosList">
                                <!-- Os dados dos usuários serão preenchidos aqui via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para listar empresas -->
        <div class="modal fade" id="listarEmpresasModal" tabindex="-1" aria-labelledby="listarEmpresasModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="listarEmpresasModalLabel">Lista de Empresas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome da Empresa</th>
                                    <th>Caminho</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="empresasList">
                                <!-- Os dados das empresas serão preenchidos aqui via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar usuário -->
        <div class="modal fade" id="editarUserModal" tabindex="-1" aria-labelledby="editarUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarUserModalLabel">Editar Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editarUserForm">
                            <input type="hidden" id="user_id"> <!-- Campo oculto para armazenar o user_id -->
                            <div class="mb-3">
                                <label for="editarUsername" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="editarUsername" required>
                            </div>
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Senha Atual</label>
                                <input type="password" class="form-control" id="currentPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control" id="newPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmNewPassword" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" id="confirmNewPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="editarEmpresaSelect" class="form-label">Selecione a Empresa</label>
                                <select class="form-select" id="editarEmpresaSelect" required>
                                    <option value="" disabled selected>Escolha uma opção</option>
                                    <!-- As opções serão preenchidas dinamicamente -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editarPerfil" class="form-label">Selecione o Perfil</label>
                                <select class="form-select" id="editarPerfil" required>
                                    <option value="" disabled selected>Escolha uma opção</option>
                                    <option value="master">Master</option>
                                    <option value="admin">Admin</option>
                                    <option value="operador">Operador</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="submitEditarUser">Salvar Alterações</button>
                    </div>
                </div>
            </div>
        </div>
</div>

    <!-- Inclusão do arquivo JavaScript separado -->
    <script src="script-conteudo1.js"></script>
    <?php include 'bootstrap_js.php'; ?>
</body>
</html>

