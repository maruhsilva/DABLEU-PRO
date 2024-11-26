function finalizarCompra(items) {
    fetch("processar_pagamento.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ items })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url; // Redireciona para o link de pagamento
            } else {
                alert("Erro ao processar o pagamento: " + data.error);
            }
        })
        .catch(error => {
            console.error("Erro ao enviar os dados para o servidor:", error);
            alert("Ocorreu um erro ao finalizar a compra.");
        });
}
