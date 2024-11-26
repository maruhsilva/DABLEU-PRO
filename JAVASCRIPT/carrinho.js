document.addEventListener("DOMContentLoaded", function () {
    carregarCarrinho();  // Carrega os produtos do carrinho na página
    atualizarBadgeCarrinho();  // Atualiza o badge com o número de itens no carrinho
    atualizarTotal();  // Calcula e exibe o subtotal, frete e total
});

// Envia o carrinho ao servidor antes de submeter o formulário
document.querySelector('form').addEventListener('submit', (e) => {
    enviarCarrinho();
});

// Função para carregar os produtos do carrinho e renderizá-los na página
function carregarCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const tabelaCarrinho = document.querySelector("#produtos-carrinho");
    const mensagemCarrinhoVazio = document.querySelector("#mensagem-carrinho-vazio");

    tabelaCarrinho.innerHTML = "";  // Limpa a tabela antes de renderizar os itens

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
            <td>R$ ${produto.preco.toFixed(2)}</td>
            <td>R$ ${(produto.preco * produto.quantidade).toFixed(2)}</td>
            <td><button class="remover-produto" data-index="${index}">Remover</button></td>
        `;
        tabelaCarrinho.appendChild(row);
    });

    atualizarTotal();
}

// Função para atualizar o badge com o número total de itens no carrinho
function atualizarBadgeCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
    const badge = document.getElementById("quantidade-itens");

    if (badge) {
        badge.textContent = totalItens;
        badge.style.display = totalItens > 0 ? "flex" : "none";
    }
}

// Função para calcular e atualizar os valores de subtotal, frete e total
function atualizarTotal() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const subtotal = carrinho.reduce((total, produto) => total + produto.preco * produto.quantidade, 0);
    const frete = calcularFrete(subtotal);
    const total = subtotal + frete;

    document.querySelector("#subtotal").textContent = `R$ ${subtotal.toFixed(2)}`;
    document.querySelector("#frete").textContent = `R$ ${frete.toFixed(2)}`;
    document.querySelector("#total").textContent = `R$ ${total.toFixed(2)}`;
}

// Calcula o frete com base no subtotal (frete grátis acima de R$ 250)
function calcularFrete(subtotal) {
    return subtotal > 250 ? 0 : 10;
}

// Função para enviar o carrinho ao servidor
function enviarCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    document.querySelector('input[name="carrinho"]').value = JSON.stringify(carrinho);
}

// Lida com ações de aumentar, diminuir ou remover produtos do carrinho
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

    localStorage.setItem("carrinho", JSON.stringify(carrinho));
    carregarCarrinho();
    atualizarBadgeCarrinho();
    atualizarTotal();
});