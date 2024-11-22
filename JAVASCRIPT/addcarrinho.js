document.addEventListener("DOMContentLoaded", function () {
    // Carrega o HTML do ícone do carrinho
    fetch('cart-badge.html')
        .then(response => response.text())
        .then(html => {
            document.querySelector("#cart-icon-container").innerHTML = html;
            // Atualiza a badge com o número atual de itens no carrinho
            atualizarBadge();
        });

    // Escuta mudanças no localStorage para atualizar a badge dinamicamente
    window.addEventListener("storage", function (event) {
        if (event.key === "carrinho") {
            atualizarBadge();
        }
    });
});

// Função para atualizar a badge
function atualizarBadge() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);

    // Seleciona o badge e o container
    const badge = document.querySelector("#cart-badge");
    const badgeContainer = document.querySelector("#cart-icon-container");

    if (badge) {
        badge.textContent = totalItens;
        badge.style.display = totalItens > 0 ? "flex" : "none"; // Mostra a badge apenas se houver itens
    } else if (totalItens > 0 && badgeContainer) {
        // Adiciona a badge dinamicamente se ainda não estiver presente
        const newBadge = document.createElement("span");
        newBadge.id = "cart-badge";
        newBadge.style.display = "flex";
        newBadge.textContent = totalItens;
        badgeContainer.appendChild(newBadge);
    }
}
