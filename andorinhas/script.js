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
    
        // Verificação se todos os campos foram preenchidos
        if (!dia || !cfc || !cd || !descricao || !comp || !valor || !local_pgto || !cp) {
            alert("Por favor, preencha todos os campos obrigatórios!");
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
        const dia = document.getElementById(`editdiaR${id_receita}`).value;
        const comp = document.getElementById(`editcompR${id_receita}`).value;
        const descricao = document.getElementById(`editdescricaoR${id_receita}`).value;
        const valor = document.getElementById(`editvalorR${id_receita}`).value;
        const banco = document.getElementById(`editbancoR${id_receita}`).value;
        const cod = document.getElementById(`editcodR${id_receita}`).value;
        const loja = document.getElementById(`editlojaR${id_receita}`).value;
    
        // Verificação se todos os campos foram preenchidos
        if (!dia || !comp || !valor || !banco || !cod || !loja) {
            alert("Por favor, preencha todos os campos obrigatórios!");
            return;
        }
    
        // Criação do objeto JSON para envio
        const data = {
            id_receita: id_receita,
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
                'Content-Type': 'application/json', // Importante definir como JSON
            },
            body: JSON.stringify(data) // Envia os dados no formato JSON
        }).then(response => {
            if (!response.ok) {
                throw new Error("Erro ao editar o receita: " + response.statusText);
            }
            return response.json();
        }).then(data => {
            if (data.success) {
                alert("Receita editado com sucesso!");
                window.location.reload();
            } else {
                alert("Erro ao editar o receita: " + data.message);
            }
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
                    throw new Error("Erro ao excluir o receita: " + response.statusText);
                }
                return response.json();
            }).then(data => {
                if (data.success) {
                    alert("Receita excluído com sucesso!");
                    window.location.reload();
                } else {
                    alert("Erro ao excluir o receita: " + data.message);
                }
            });
        }
    }
    window.excluirPagamento = excluirReceita;


});