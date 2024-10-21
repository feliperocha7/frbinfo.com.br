document.addEventListener("DOMContentLoaded", function () {
    ///PAGAMENTOS///
    function salvarPagamento(pgtoId) {
        const dia = document.getElementById(`editdia${pgtoId}`).value;
        const cfc = document.getElementById(`editcfc${pgtoId}`).value;
        const cd = document.getElementById(`editcd${pgtoId}`).value;
        const descricao = document.getElementById(`editdescricao${pgtoId}`).value;
        const comp = document.getElementById(`editcomp${pgtoId}`).value;
        const valor = document.getElementById(`editvalor${pgtoId}`).value;
        const local_pgto = document.getElementById(`editlocalpgto${pgtoId}`).value;
        const cp = document.getElementById(`editcp${pgtoId}`).value;
        const pago = document.getElementById(`editpago${pgtoId}`).checked ? 1 : 0; // Captura o valor correto do checkbox
    
        // Verificação se algum campo foi alterado
        const originalDia = document.getElementById(`editdia${pgtoId}`).getAttribute('data-original');
        const originalCfc = document.getElementById(`editcfc${pgtoId}`).getAttribute('data-original');
        const originalCd = document.getElementById(`editcd${pgtoId}`).getAttribute('data-original');
        const originalDescricao = document.getElementById(`editdescricao${pgtoId}`).getAttribute('data-original');
        const originalComp = document.getElementById(`editcomp${pgtoId}`).getAttribute('data-original');
        const originalValor = document.getElementById(`editvalor${pgtoId}`).getAttribute('data-original');
        const originalLocalPgto = document.getElementById(`editlocalpgto${pgtoId}`).getAttribute('data-original');
        const originalCp = document.getElementById(`editcp${pgtoId}`).getAttribute('data-original');
        const originalPago = document.getElementById(`editpago${pgtoId}`).getAttribute('data-original');
    
        if (
            dia === originalDia && 
            cfc === originalCfc && 
            cd === originalCd && 
            descricao === originalDescricao && 
            comp === originalComp && 
            valor === originalValor && 
            local_pgto === originalLocalPgto && 
            cp === originalCp && 
            pago.toString() === originalPago
        ) {
            alert("Nenhuma alteração foi feita.");
            return;
        }
    
        // Criação do objeto JSON para envio
        const data = {
            pgtoId: pgtoId,
            dia: dia,
            cfc: cfc,
            cd: cd,
            descricao: descricao,
            comp: comp,
            valor: valor,
            local_pgto: local_pgto,
            cp: cp,
            pago: pago // Envia o valor de pago corretamente (1 ou 0)
        };
    
        // Enviando os dados via fetch
        fetch('updatePagamento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Importante definir como JSON
            },
            body: JSON.stringify(data) // Envia os dados no formato JSON
        }).then(response => {
            if (!response.ok) {
                throw new Error("Erro ao editar o pagamento: " + response.statusText);
            }
            return response.json();
        }).then(data => {
            if (data.success) {
                alert("Pagamento editado com sucesso!");
                window.location.reload();
            } else {
                alert("Erro ao editar o pagamento: " + data.message);
            }
        });
    }
    
    
    
    window.salvarPagamento = salvarPagamento;

    function excluirPagamento(pgtoId) {
        if (confirm("Você tem certeza que deseja excluir este pagamento?")) {
            fetch('deletePagamento.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ pgtoId: pgtoId })
            }).then(response => {
                if (!response.ok) {
                    throw new Error("Erro ao excluir o pagamento: " + response.statusText);
                }
                return response.json();
            }).then(data => {
                if (data.success) {
                    alert("Pagamento excluído com sucesso!");
                    window.location.reload();
                } else {
                    alert("Erro ao excluir o pagamento: " + data.message);
                }
            });
        }
    }
    window.excluirPagamento = excluirPagamento;

    ///RECEITAS///
    function salvarReceita(id_receita) {
        // Obtém os valores atuais dos campos do formulário
        const dia = document.getElementById(`editdiaR${id_receita}`).value;
        const comp = document.getElementById(`editcompR${id_receita}`).value;
        const descricao = document.getElementById(`editdescricaoR${id_receita}`).value;
        const valor = document.getElementById(`editvalorR${id_receita}`).value;
        const banco = document.getElementById(`editbancoR${id_receita}`).value;
        const cod = document.getElementById(`editcodR${id_receita}`).value;
        const loja = document.getElementById(`editlojaR${id_receita}`).value;
    
        // Obtém os valores originais para comparação (por exemplo, você pode armazená-los no HTML com `data-*` attributes)
        const originalDia = document.getElementById(`editdiaR${id_receita}`).getAttribute('data-original');
        const originalComp = document.getElementById(`editcompR${id_receita}`).getAttribute('data-original');
        const originalDescricao = document.getElementById(`editdescricaoR${id_receita}`).getAttribute('data-original');
        const originalValor = document.getElementById(`editvalorR${id_receita}`).getAttribute('data-original');
        const originalBanco = document.getElementById(`editbancoR${id_receita}`).getAttribute('data-original');
        const originalCod = document.getElementById(`editcodR${id_receita}`).getAttribute('data-original');
        const originalLoja = document.getElementById(`editlojaR${id_receita}`).getAttribute('data-original');
    
        // Verificação se todos os campos foram preenchidos
        if (!dia || !descricao || !comp || !valor || !banco || !cod || !loja) {
            alert("Por favor, preencha todos os campos obrigatórios!");
            return;
        }
    
        // Verificar se algum valor foi modificado
        if (dia === originalDia && comp === originalComp && descricao === originalDescricao &&
            valor === originalValor && banco === originalBanco && cod === originalCod && loja === originalLoja) {
            alert("Nenhuma alteração foi feita.");
            return;
        }
    
        // Criação do objeto JSON para envio
        const data = {
            receita_id: id_receita,
            dia: dia,
            comp: comp,
            descricao: descricao,
            valor: valor,
            banco: banco,
            cod: cod,
            loja: loja
        };
    
        // Enviando os dados via fetch
        fetch('updateReceita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erro ao editar a receita: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert("Receita editada com sucesso!");
                window.location.reload();
            } else {
                alert("Erro ao editar a receita: " + data.message);
            }
        })
        .catch(error => {
            alert("Ocorreu um erro: " + error.message);
        });
    }
    
    window.salvarReceita = salvarReceita;


    function excluirReceita(id_receita) {
        if (confirm("Você tem certeza que deseja excluir esta receita?")) {
            fetch('deleteReceita.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_receita: id_receita })
            }).then(response => {
                if (!response.ok) {
                    throw new Error("Erro ao excluir a receita: " + response.statusText);
                }
                return response.json();
            }).then(data => {
                if (data.success) {
                    alert("Receita excluída com sucesso!");
                    window.location.reload();
                } else {
                    alert("Erro ao excluir a receita: " + data.message);
                }
            });
        }
    }
    window.excluirReceita = excluirReceita;


});