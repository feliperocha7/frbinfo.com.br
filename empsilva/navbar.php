<nav class="navbar">
    <a href="painel.php" class="navbar-logo">
        <img src="img/logo4P-navbar60px.png" alt="FBI - Felipe Barros Informática Logo" class="navbar-logo-img">
    </a>
    <div class="navbar-buttons">
        <button class="nav-btn" onclick="window.location.href='painel.php';">Painel</button>
        <button class="nav-btn" onclick="window.location.href='clientes.php';">Clientes</button>
        <button class="nav-btn" onclick="window.location.href='emprestimos.php';">Empréstimos</button>
    </div>
    <div class="navbar-user">
        <span class="user-name">Olá, <?php echo $_SESSION['user']; ?></span>
        <button class="btn-settings" onclick="openSettingsModal()">Configurações</button>
        <a href="../logout.php" class="btn-logout">Sair</a>
    </div>
</nav>
<!-- Modal para Configurações -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Configurações do Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="settingsForm">
                    <div class="form-group">
                        <label for="username">Nome de Usuário</label>
                        <input type="text" class="form-control" id="username" value="<?php echo $_SESSION['user']; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="perfil">Perfil</label>
                        <input type="perfil" class="form-control" id="perfil" value="<?php echo $_SESSION['perfil']; ?>" disabled>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>




<script>
        function saveSettings() {
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();
    
        // Validação simples
        if (password !== confirmPassword) {
            alert("As senhas não conferem.");
            return;
        }
    
        // Aqui você pode fazer uma chamada AJAX para salvar os dados
        $.ajax({
            url: 'saveSettings.php',
            type: 'POST',
            data: { email: email, password: password },
            success: function(response) {
                // Lidar com a resposta do servidor
                alert("Configurações salvas com sucesso!");
                $('#settingsModal').modal('hide');
            },
            error: function() {
                alert("Erro ao salvar as configurações.");
            }
        });
    }
    

    function openSettingsModal() {
        // Aqui você pode preencher os campos do modal com os dados do usuário
        // Por exemplo, você pode fazer uma chamada AJAX para obter os dados do usuário logado
        $('#settingsModal').modal('show');
    }

    function toggleNavbar() {
        const buttons = document.querySelector('.navbar-buttons');
        buttons.style.display = (buttons.style.display === 'flex' || buttons.style.display === '') ? 'none' : 'flex';
    }

</script>


