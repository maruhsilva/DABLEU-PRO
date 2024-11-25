function enviarCarrinhoParaPHP() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho"));

    if (!carrinho || carrinho.length === 0) {
        alert('Carrinho vazio');
        return;
    }

    fetch('processar_pagamento.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            carrinho,
            metodo_pagamento: 'cartao' // Ou 'pix', dependendo do caso
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            window.location.href = data.redirect_url;
        } else {
            alert(data.mensagem || 'Erro no processamento.');
        }
    })
    .catch(error => console.error('Erro:', error));
}
