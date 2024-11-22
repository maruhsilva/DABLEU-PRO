document.addEventListener("DOMContentLoaded", function () {
    carregarCarrinho();
    atualizarBadgeCarrinho(); // Atualiza o badge ao carregar a página
    atualizarTotal(); // Atualiza o total ao carregar a página
});

// Carrega o carrinho e exibe os produtos na tabela
function carregarCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const tabelaCarrinho = document.querySelector("#produtos-carrinho");
    const mensagemCarrinhoVazio = document.querySelector("#mensagem-carrinho-vazio");

    // Limpar a tabela antes de renderizar os itens novamente
    tabelaCarrinho.innerHTML = "";

    if (carrinho.length === 0) {
        mensagemCarrinhoVazio.style.display = "block";
        return;
    }

    mensagemCarrinhoVazio.style.display = "none";

    carrinho.forEach((produto, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><img src="${produto.imagem}" alt="${produto.nome}" class="img-produto"></td>
            <td>${produto.nome}</td>
            <td>${produto.tamanho}</td>
            <td>
                <button class="diminuir-quantidade" data-index="${index}">-</button>
                <span>${produto.quantidade}</span>
                <button class="aumentar-quantidade" data-index="${index}">+</button>
            </td>
            <td>R$ ${produto.preco.toFixed(2)}</td> <!-- Preço unitário -->
            <td>R$ ${(produto.preco * produto.quantidade).toFixed(2)}</td> <!-- Preço total do item -->
            <td><button class="remover-produto" data-index="${index}">Remover</button></td>
        `;
        tabelaCarrinho.appendChild(row);
    });
}

// Atualiza o número de itens no badge
function atualizarBadgeCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
    const badge = document.getElementById("quantidade-itens");

    badge.textContent = totalItens;

    if (totalItens > 0) {
        badge.style.display = "flex";
    } else {
        badge.style.display = "none";
    }
}

// Calcula e exibe o total do carrinho
function atualizarTotal() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const subtotal = carrinho.reduce((total, produto) => total + produto.preco * produto.quantidade, 0);
    const frete = calcularFrete(subtotal);
    const total = subtotal + frete;

    document.querySelector("#subtotal").textContent = `R$ ${subtotal.toFixed(2)}`;
    document.querySelector("#frete").textContent = `R$ ${frete.toFixed(2)}`;
    document.querySelector("#total").textContent = `R$ ${total.toFixed(2)}`;
}

// Função para calcular o frete
function calcularFrete(subtotal) {
    return subtotal > 250 ? 0 : 10; // Frete grátis para compras acima de R$ 250
}

// Lida com ações no carrinho
document.querySelector("#produtos-carrinho").addEventListener("click", function (event) {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const target = event.target;
    const index = parseInt(target.dataset.index);

    if (target.classList.contains("diminuir-quantidade")) {
        if (carrinho[index].quantidade > 1) {
            carrinho[index].quantidade--;
        } else {
            carrinho.splice(index, 1);
        }
    } else if (target.classList.contains("aumentar-quantidade")) {
        carrinho[index].quantidade++;
    } else if (target.classList.contains("remover-produto")) {
        carrinho.splice(index, 1);
    }

    // Atualiza o LocalStorage e recarrega os elementos
    localStorage.setItem("carrinho", JSON.stringify(carrinho));
    carregarCarrinho();
    atualizarBadgeCarrinho();
    atualizarTotal();
});
