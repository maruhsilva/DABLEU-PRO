// Função para enviar os dados do carrinho para o PHP
function enviarCarrinhoParaPHP() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho"));

    // Verifica se o carrinho tem produtos
    if (carrinho && carrinho.length > 0) {
        // Cria o formulário dinamicamente
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'processar_pagamento.php'; // A URL do seu PHP

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

        // Envia o formulário
        document.body.appendChild(form);
        form.submit();
    } else {
        alert('Carrinho vazio');
    }
}

// Exemplo de chamada para enviar o carrinho ao servidor
enviarCarrinhoParaPHP();
