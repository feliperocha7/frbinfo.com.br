document.addEventListener("DOMContentLoaded", function () {

    ///------------------------------------------------------------///
    ///FUNÇÕES PARA A PÁGINA DE CADASTRO E MANIPULAÇÃO DE CLIENTES///
    ///Função para salvar um novo cliente
    function saveNewClient() {
	    const newClientName = document.getElementById("clientName").value; // ID do nome
	    let newClientCpf = document.getElementById("new-client-cpf").value; // ID do CPF
	    const newClientIndicator = document.getElementById("new-client-indicator").value; // ID da indicação
	    const newClientDocument = document.getElementById("clientDocument").files[0]; // ID do documento
	    const newClientResidence = document.getElementById("clientResidence").files[0]; // ID do comprovante de residência
	
	    // Verificação se o CPF foi preenchido, caso contrário, define "não preenchido"
	    if (!newClientCpf) {
	        newClientCpf = "não preenchido";
	    }
	
	    // Verificação se os campos obrigatórios foram preenchidos (exceto os arquivos de documento)
	    if (!newClientName || !newClientIndicator) {
	        alert("Por favor, preencha todos os campos obrigatórios!");
	        return;
	    }
	
	    // Criação do FormData para envio
	    const formData = new FormData();
	    formData.append("newClientName", newClientName);
	    formData.append("newClientCpf", newClientCpf);
	    formData.append("newClientIndicator", newClientIndicator);
	
	    // Somente adicionar os arquivos ao FormData se eles foram selecionados
	    if (newClientDocument) {
	        formData.append("newClientDocument", newClientDocument);
	    }
	    if (newClientResidence) {
	        formData.append("newClientResidence", newClientResidence);
	    }
	
	    // Enviando os dados via fetch
	    fetch('saveNewClient.php', {
	        method: 'POST',
	        body: formData
	    }).then(response => {
	        if (!response.ok) {
	            throw new Error("Erro ao salvar o cliente: " + response.statusText);
	        }
	        return response.json();
	    }).then(data => {
	        if (data.success) {
	            alert("Novo cliente salvo com sucesso!");
	            window.location.reload(); // Recarregar a página ou redirecionar
	        } else {
	            alert("Erro ao salvar o novo cliente: " + data.message);
	        }
	    }).catch(error => {
	        console.error('Erro:', error);
	        alert("Ocorreu um erro ao tentar salvar o cliente.");
	    });
	}

    window.saveNewClient = saveNewClient;

    //FUNÇÃO PARA HABILITAR CAMPOS DE FORMULARIO DE CLIENTES
    let controleEditClient = false; // Variável global para controlar o estado de edição

    function enableEditClient(clientId) {
        const fields = [
            "edit-client-name",
            "edit-client-cpf",
            "edit-client-document",
            "edit-client-residence"
        ];

        // Inverte o estado de edição
        controleEditClient = !controleEditClient; 

        fields.forEach(field => {
            const input = document.getElementById(`${field}${clientId}`);
            if (input) {
                input.disabled = !controleEditClient; // Habilita ou desabilita o campo baseado no estado de controle
            }
        });
    }
    window.enableEditClient = enableEditClient;  // Adicionando enableEdit

    //FUNÇÃO PARA SALVAR EDIÇÃO DE CLIENTES, SÓ FUNCIONA SE A EDIÇÃO ESTIVER LIBERADA
    function saveClient(clientId) {
        if(controleEditClient){
            const clientName = document.getElementById(`edit-client-name${clientId}`).value;
            const clientCpf = document.getElementById(`edit-client-cpf${clientId}`).value;
            const clientIndicator = document.getElementById(`edit-client-indicator${clientId}`).value;
            const clientDocument = document.getElementById(`edit-client-document${clientId}`).files[0];
            const clientResidence = document.getElementById(`edit-client-residence${clientId}`).files[0];

            // Verificação se todos os campos foram preenchidos
            if (!clientName || !clientCpf || !clientIndicator) {
                alert("Por favor, preencha todos os campos obrigatórios!");
                return;
            }

            // Criação do FormData para envio
            const formData = new FormData();
            formData.append("clientId", clientId);
            formData.append("clientName", clientName);
            formData.append("clientCpf", clientCpf);
            formData.append("clientIndicator", clientIndicator);
            if (clientDocument) formData.append("clientDocument", clientDocument);
            if (clientResidence) formData.append("clientResidence", clientResidence);

            // Enviando os dados via fetch
            fetch('updateClient.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) {
                    throw new Error("Erro ao editar o cliente: " + response.statusText);
                }
                return response.json();
            }).then(data => {
                if (data.success) {
                    alert("Cliente editado com sucesso!");
                    window.location.reload();
                } else {
                    alert("Erro ao editar o cliente: " + data.message);
                }
            });
        }else{
            alert("Edição desabilitada!")
        }
        
    }
    window.saveClient = saveClient;    // Adicionando saveClient

    //FUNÇÃO PARA DELETAR CLIENTE
    /////IMPLEMENTAR DEPOIS UMA INATIVAÇÃO E NÃO EXCLUSÃO, E INATIVAR SOMENTE SE ELE NÃO TIVER NENHUM CONTRATO ATIVO
    function deleteClient(clientId) {
        if (confirm("Você tem certeza que deseja excluir este cliente?")) {
            fetch('deleteClient.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ clientId: clientId })
            }).then(response => {
                if (!response.ok) {
                    throw new Error("Erro ao excluir o cliente: " + response.statusText);
                }
                return response.json();
            }).then(data => {
                if (data.success) {
                    alert("Cliente excluído com sucesso!");
                    window.location.reload();
                } else {
                    alert("Erro ao excluir o cliente: " + data.message);
                }
            });
        }
    }
    window.deleteClient = deleteClient;  // Adicionando deleteClient
    ///------------------------------------------------------///
    ///FIM DAS FUNÇÕES DE CADASTRO E MANIPULAÇÃO DE CLIENTES///


    ///-------------------------------------------------------///
    ///INICIO FUNÇÕES DE CRIAÇÃO E MANIPULAÇÃO DE EMPRESTIMOS///
    /// Função para salvar um novo empréstimo
    function saveNewLoan() {
        const clientId = document.getElementById("clientId").value; // ID do dropdown do cliente
        const loanAmount = document.getElementById("loanAmount").value; // Valor do empréstimo
        const loanDuration = document.getElementById("loanDuration").value; // Duração do empréstimo
    
        // Verificação se todos os campos foram preenchidos
        if (!clientId || !loanAmount || !loanDuration) {
            alert("Por favor, preencha todos os campos obrigatórios!");
            return;
        }
    
        // Criação do FormData para envio
        const formData = new FormData();
        formData.append("clientId", clientId);
        formData.append("loanAmount", loanAmount);
        formData.append("loanDuration", loanDuration);
    
        // Enviando os dados via fetch
        fetch('saveNewLoan.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            return response.json(); // Espera resposta em JSON
        })
        .then(data => {
            if (data.success) {
                alert("Empréstimo salvo com sucesso!");
                window.location.reload();
            } else {
                alert("Erro ao salvar o empréstimo: " + data.message);
            }
        })
        .catch(error => {
            console.error("Erro:", error);
        });
    }
    window.saveNewLoan = saveNewLoan;

    // Função para buscar as parcelas do servidor
    function fetchInstallments(loanId) {
        $.ajax({
            url: 'fetchInstallments.php', // O arquivo PHP que você vai criar
            type: 'POST',
            data: { loanId: loanId },
            success: function(data) {
                // Preenche o modal de parcelas com a resposta do servidor
                $('#installmentsTableContainer').html(data);
                $('#installmentsModal').modal('show');
            },
            error: function() {
                alert('Erro ao buscar parcelas.');
            }
        });
    }
    window.fetchInstallments = fetchInstallments;
    
    function payInstallment(loanId, installmentId) {
        const data = new FormData();
        data.append('loanId', loanId);
        data.append('installmentId', installmentId);
    
        fetch('payInstallment.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Parcela paga com sucesso!');
                location.reload(); // Atualiza a página para refletir as alterações
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Erro:', error));
    }
    window.payInstallment = payInstallment;
    
    //PREENCHE MODAL DAS PARCELAS
    function showInstallments(loanId, qtdParcelas) {
        const totalParcelas = qtdParcelas;
        console.log('Fetching installments for loan ID:', loanId); // Debug
        $.ajax({
            url: 'getInstallments.php',
            type: 'POST',
            data: { loanId: loanId },
            success: function(response) {
                console.log('AJAX response:', response); // Debug
                try {
                    const data = JSON.parse(response);
                    let installmentsHtml = '<table class="table"><thead><tr><th>Parcela</th><th>Valor</th><th>Juros</th><th>Total com Juros</th><th>Vencimento</th><th>Status</th></tr></thead><tbody>';
                    data.forEach(installment => {
                        const installmentValue = parseFloat(installment.valor);
                        const installmentNumber = installment.numero_parcela;
                        const juros = ((installmentValue * totalParcelas)-(installmentValue * (installmentNumber - 1))) * 0.15;
                        const comJuros = installmentValue + juros;
                        installmentsHtml += `<tr>
                            <td>Parcela ${installmentNumber}</td>
                            <td>R$ ${installmentValue.toFixed(2)}</td>
                            <td>R$ ${juros.toFixed(2)}</td>
                            <td>R$ ${comJuros.toFixed(2)}</td>
                            <td>${installment.data_vencimento}</td>
                            <td>
                                <input type="checkbox" class="installment-checkbox" data-id="${installment.id}" value="${installmentValue}" ${installment.pago ? 'disabled checked' : ''}>
                                ${installment.pago ? 'Pago' : 'Pendente'}
                            </td>
                        </tr>`;
                    });
                    installmentsHtml += '</tbody></table>';
                    installmentsHtml += '<button class="btn btn-primary" onclick="savePayments(' + loanId + ')">Salvar</button>';
                    $('#installmentsTableContainer').html(installmentsHtml);
                    $('#installmentsModal').modal('show');
                } catch (error) {
                    console.error('Erro ao processar os dados:', error);
                    alert('Erro ao carregar as parcelas.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('Erro ao carregar as parcelas.');
            }
        });
    }
    
    window.showInstallments = showInstallments;

    //Function para pagar parcelas
    function savePayments(loanId) {
        const checkedBoxes = $('.installment-checkbox:checked');
        const payments = [];
    
        checkedBoxes.each(function() {
            const checkbox = $(this);
            // Verifica se o checkbox está habilitado antes de adicionar
            if (!checkbox.prop('disabled')) {
                payments.push({
                    id: checkbox.data('id'),
                    value: parseFloat(checkbox.val())
                });
            }
        });
    
        if (payments.length === 0) {
            alert('Nenhuma parcela selecionada para pagamento.');
            return;
        }
    
        console.log('Loan ID:', loanId);
        console.log('Payments:', JSON.stringify(payments)); // Para ver o conteúdo de payments
    
        $.ajax({
            url: 'savePayments.php',
            type: 'POST',
            data: { payments: JSON.stringify(payments), loanId: loanId }, // Usando o loanId do modal
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert('Pagamentos salvos com sucesso!');
                    $('#installmentsModal').modal('hide');
                } else {
                    alert('Erro: ' + (result.message || 'Erro ao salvar pagamentos.'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro:', error);
                console.log('Status:', status);
                console.log('Resposta do servidor:', xhr.responseText);
                alert('Erro ao salvar pagamentos. Veja o console para mais detalhes.');
            }
        });
    }
    window.savePayments = savePayments;

    function reloadPage() {
        location.reload();
    }
    window.reloadPage = reloadPage;

    const newLoanForm = document.getElementById('newLoanForm');
    //console.log('newLoanForm:', newLoanForm); // Verifica se o elemento foi encontrado
    
    if (newLoanForm) {
        newLoanForm.addEventListener('submit', function(event) {
            const duration = document.getElementById('loanDuration').value;
            const amount = document.getElementById('loanAmount').value;
    
            if (duration < 1 || duration > 12) {
                alert("A duração deve estar entre 1 e 12 meses.");
                event.preventDefault(); // Impede o envio do formulário
            }
    
            if (amount <= 0) {
                alert("O valor do empréstimo deve ser maior que zero.");
                event.preventDefault(); // Impede o envio do formulário
            }
        });
    }

    $('#myModal').on('hidden.bs.modal', function () {
        // Recarregar a página
        location.reload(); // Ou você pode usar: window.location.href = window.location.href;
    });



    
    






});