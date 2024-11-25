// Função para enviar os dados do carrinho para o PHP
function enviarCarrinhoParaPHP() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho"));

    // Verifica se o carrinho tem produtos
    if (carrinho && carrinho.length > 0) {
        // Cria o formulário dinamicamente
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'processar_pagamento.php'; // Aqui você pode enviar para um arquivo que processa o carrinho, sem redirecionar

        // Adiciona o carrinho como um campo hidden (oculto) no formulário
        const inputCarrinho = document.createElement('input');
        inputCarrinho.type = 'hidden';
        inputCarrinho.name = 'carrinho';
        inputCarrinho.value = JSON.stringify(carrinho); // Converte o carrinho para JSON
        form.appendChild(inputCarrinho);

        // Adiciona outros campos do cliente (se necessário)
        const inputMetodoPagamento = document.createElement('input');
        inputMetodoPagamento.type = 'hidden';
        inputMetodoPagamento.name = 'metodo_pagamento';
        inputMetodoPagamento.value = 'cartao'; // Ou 'pix', dependendo da escolha do usuário
        form.appendChild(inputMetodoPagamento);

        // Envia o formulário sem redirecionar
        fetch('processar_pagamento.php', {
            method: 'POST',
            body: new FormData(form),
        })
        .then(response => response.json()) // Supondo que o servidor retorne JSON
        .then(data => {
            if (data.sucesso) {
                alert('Carrinho processado com sucesso!');
                // Aqui você pode exibir os dados ou atualizar a página sem redirecionar
            } else {
                alert('Houve um erro ao processar o carrinho.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar o carrinho.');
        });
    } else {
        alert('Carrinho vazio');
    }
}

// Exemplo de chamada para enviar o carrinho ao servidor
// Isso pode ser chamado quando o usuário clicar no botão de finalizar compra
enviarCarrinhoParaPHP();
