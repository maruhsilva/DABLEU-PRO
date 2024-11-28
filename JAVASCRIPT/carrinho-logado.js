
function enviarDadosCarrinho(dadosCarrinho) {
    // Verifique se os dados estão corretos
    if (dadosCarrinho.length === 0) {
        console.error('Carrinho vazio! Não é possível enviar dados vazios.');
        return;
    }

    const dados = {
        carrinho: dadosCarrinho,  // Não é necessário JSON.stringify aqui
        
    };


    console.log("Carrinho antes de enviar:", JSON.stringify(carrinho));

fetch('processar_pagamento.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        carrinho: carrinho // Envia apenas os dados do carrinho
    })
})
.then(response => response.json())
.then(data => {
    console.log("Resposta do servidor:", data);
})
.catch(error => {
    console.error("Erro ao enviar dados:", error);
});
}
