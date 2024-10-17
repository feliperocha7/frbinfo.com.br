//FUNÇÃO PARA CADASTRO DE EMPRESAS
$(document).ready(function() {
    // Evento para o botão de cadastro da empresa
    $('#submitCadastroEmpresa').on('click', function() {
        var nome = $('#empresaNome').val();
        var caminho = $('#empresaCaminho').val();

        // Validação simples
        if (!nome || !caminho) {
            alert("Por favor, preencha todos os campos.");
            return;
        }

        // Fazer a requisição AJAX
        $.ajax({
            url: 'cadastrar_empresa.php', // O arquivo PHP que processa a inserção
            type: 'POST',
            data: {
                empresaNome: nome,
                empresaCaminho: caminho
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Fechar o modal após o sucesso
                    $('#cadastroEmpresaModal').modal('hide');
                    // Limpar os campos do formulário
                    $('#cadastroEmpresaForm')[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Erro ao processar a requisição.");
            }
        });
    });
});


//FUNÇÃO PARA COLETAR OS DADOS DA EMPRESA PARA INSERIR NO SELECT DO MODAL DE CADASTRO DE USUARIOS
$(document).ready(function() {
    // Função para preencher o select com as empresas
    function loadEmpresas() {
        $.ajax({
            url: 'consultar_empresas.php', // O arquivo que retorna as empresas
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (Array.isArray(data)) {
                    const select = $('#empresaSelect');
                    data.forEach(function(empresa) {
                        select.append(`<option value="${empresa.id}">${empresa.nome}</option>`);
                    });
                } else {
                    console.error('Erro ao carregar empresas:', data.message);
                }
            },
            error: function() {
                console.error('Erro ao fazer a requisição.');
            }
        });
    }

    // Chama a função para carregar as empresas
    loadEmpresas();
});

//FUNÇÃO PARA CADASTRO DE USUARIOS
$(document).ready(function() {
    // Função para cadastrar usuário
    $('#submitCadastro').on('click', function() {
        var username = $('#username').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();
        var perfil = $('#perfil').val();
        var empresaId = $('#empresaSelect').val(); // ID da empresa selecionada

        // Validação simples
        if (!username || !password || !confirmPassword || !perfil || !empresaId) {
            alert("Por favor, preencha todos os campos.");
            return;
        }

        // Verifica se as senhas coincidem
        if (password !== confirmPassword) {
            alert("As senhas não coincidem.");
            return;
        }

        // Fazer a requisição AJAX para cadastrar o usuário
        $.ajax({
            url: 'cadastrar_usuario.php', // O arquivo PHP que processa a inserção
            type: 'POST',
            data: {
                username: username,
                password: password,
                perfil: perfil,
                empresaId: empresaId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Fechar o modal após o sucesso
                    $('#cadastroModal').modal('hide');
                    // Limpar os campos do formulário
                    $('#cadastroForm')[0].reset();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Erro ao processar a requisição.");
            }
        });
    });
});



$(document).ready(function() {
    // Carregar a lista de usuários ao abrir o modal
    $('#listarUsuariosModal').on('show.bs.modal', function () {
        $.ajax({
            url: 'listar_usuarios.php', // O arquivo PHP que retorna a lista de usuários
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var usuariosList = $('#usuariosList');
                usuariosList.empty(); // Limpa a lista antes de adicionar novos dados
                $.each(response, function(index, usuario) {
                    usuariosList.append(
                        `<tr>
                            <td>${usuario.id}</td>
                            <td>${usuario.usuario}</td>
                            <td>${usuario.perfil}</td>
                            <td>${usuario.produto}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${usuario.id}" data-usuario="${usuario.usuario}" data-perfil="${usuario.perfil}" data-produto="${usuario.produto}">Editar</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${usuario.id}">Excluir</button>
                            </td>
                        </tr>`
                    );
                });
            },
            error: function() {
                alert("Erro ao carregar usuários.");
            }
        });
    });

    // Abrir o modal de edição ao clicar no botão "Editar"
    $(document).on('click', '.edit-btn', function() {
        var userId = $(this).data('id');
        var username = $(this).data('usuario');
        var perfil = $(this).data('perfil');
        var produto = $(this).data('produto');

        // Preencher os campos do modal de edição
        $('#user_id').val(userId);
        $('#editarUsername').val(username);
        $('#editarPerfil').val(perfil);
        //$('#editarEmpresaSelect').val(produto); // Ajuste conforme a sua implementação
        loadEmpresas1(produto);
        // Mostrar o modal de edição
        $('#editarUserModal').modal('show');
    });

    function loadEmpresas1(selectedEmpresaId = null) {
        $.ajax({
            url: 'consultar_empresas.php', // O arquivo que retorna as empresas
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (Array.isArray(data)) {
                    const select = $('#editarEmpresaSelect');
                    select.empty(); // Limpa as opções existentes
                    
                    data.forEach(function(empresa) {
                        // Verifica se o ID da empresa atual corresponde ao ID da empresa selecionada
                        const selected = (selectedEmpresaId && selectedEmpresaId === empresa.id) ? 'selected' : '';
                        select.append(`<option value="${empresa.id}" ${selected}>${empresa.nome}</option>`);
                    });
                } else {
                    console.error('Erro ao carregar empresas:', data.message);
                }
            },
            error: function() {
                console.error('Erro ao fazer a requisição.');
            }
        });
    }
    

    // Salvar alterações no usuário
    $(document).on('click', '#submitEditarUser', function() {
        const userId = $('#user_id').val();
        const username = $('#editarUsername').val();
        const currentPassword = $('#currentPassword').val(); // Campo para senha atual
        const newPassword = $('#newPassword').val(); // Campo para nova senha
        const confirmPassword = $('#confirmNewPassword').val(); // Campo para confirmação da nova senha
        const empresaId = $('#editarEmpresaSelect').val(); // ID da empresa selecionada
        const perfil = $('#editarPerfil').val(); // Perfil selecionado
    
        // Validação adicional pode ser necessária
        if (newPassword !== confirmPassword) {
            alert('As senhas novas não coincidem.');
            return;
        }
    
        // Faça a chamada AJAX para atualizar o usuário
        $.ajax({
            url: 'atualizar_usuario.php',
            type: 'POST',
            data: {
                user_id: userId,
                usuario: username,
                senha_atual: currentPassword,
                nova_senha: newPassword,
                produto: empresaId,
                perfil: perfil
            },
            dataType: 'json',
            success: function(response) {
                // Verifica se a resposta é bem-sucedida
                if (response.success) {
                    alert(response.message); // Exibe a mensagem de sucesso
                    $('#editarUserModal').modal('hide'); // Fecha o modal
                } else {
                    alert(response.message); // Exibe a mensagem de erro
                }
            },
            error: function(xhr, status, error) {
                // Lida com o erro de forma mais específica
                console.error('Erro ao atualizar usuário:', error);
                alert("Erro ao atualizar usuário."); // Mensagem de erro genérica
            }
        });
    });
    



    // Carregar lista de empresas
    $('#listarEmpresasModal').on('show.bs.modal', function () {
        $.ajax({
            url: 'listar_empresas.php', // O arquivo PHP que retorna a lista de empresas
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var empresasList = $('#empresasList');
                empresasList.empty(); // Limpa a lista antes de adicionar novos dados
                $.each(response, function(index, empresa) {
                    empresasList.append(
                        `<tr>
                            <td>${empresa.id}</td>
                            <td>${empresa.nome}</td>
                            <td>${empresa.caminho}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${empresa.id}">Editar</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${empresa.id}">Excluir</button>
                            </td>
                        </tr>`
                    );
                });
            },
            error: function() {
                alert("Erro ao carregar empresas.");
            }
        });
    });

    
});
// Excluir usuário
$(document).on('click', '.delete-btn', function() {
    var usuarioId = $(this).data('id');
    if (confirm("Você tem certeza que deseja excluir este usuário?")) {
        $.ajax({
            url: 'excluir_usuario.php', // O arquivo PHP que irá excluir o usuário
            type: 'POST',
            data: { id: usuarioId },
            success: function(response) {
                alert(response.message);
                // Recarregar a lista de usuários
                $('#listarUsuariosModal').modal('hide');
                $('#listarUsuariosModal').modal('show');
            },
            error: function() {
                alert("Erro ao excluir usuário.");
            }
        });
    }
});

// Excluir empresa
$(document).on('click', '.delete-btn', function() {
    var empresaId = $(this).data('id');
    if (confirm("Você tem certeza que deseja excluir esta empresa?")) {
        $.ajax({
            url: 'excluir_empresa.php', // O arquivo PHP que irá excluir a empresa
            type: 'POST',
            data: { id: empresaId },
            success: function(response) {
                alert(response.message);
                // Recarregar a lista de empresas
                $('#listarEmpresasModal').modal('hide');
                $('#listarEmpresasModal').modal('show');
            },
            error: function() {
                alert("Erro ao excluir empresa.");
            }
        });
    }
});


