document.addEventListener("DOMContentLoaded", function () {
    // Inicializa os dados
    carregarCarrinho();
    atualizarTotal();
    atualizarBadgeCarrinho();

    // Adiciona o ouvinte para o evento customizado de atualização do carrinho
    document.addEventListener('atualizarCarrinho', function () {
        atualizarBadgeCarrinho();  // Atualiza a badge quando o carrinho mudar
        carregarCarrinho();        // Atualiza a lista de produtos
        atualizarTotal();          // Atualiza o total
    });
});

// Função para atualizar a badge com o número total de itens no carrinho
function atualizarBadgeCarrinho() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
    const badge = document.getElementById("quantidade-itens");

    if (badge) {
        badge.textContent = totalItens;
        badge.style.display = totalItens > 0 ? "flex" : "none";
    }
}

// Função para carregar os produtos do carrinho na página
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
}

// Função para atualizar o total do carrinho
function atualizarTotal() {
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    const subtotal = carrinho.reduce((total, produto) => total + produto.preco * produto.quantidade, 0);
    const frete = calcularFrete(subtotal);
    const total = subtotal + frete;

    document.querySelector("#subtotal").textContent = `R$ ${subtotal.toFixed(2)}`;
    document.querySelector("#frete").textContent = `R$ ${frete.toFixed(2)}`;
    document.querySelector("#total").textContent = `R$ ${total.toFixed(2)}`;
}

// Calcula o frete com base no subtotal
function calcularFrete(subtotal) {
    return subtotal > 250 ? 0 : 10;
}

// Função para aumentar, diminuir ou remover produtos do carrinho
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

    // Salva o carrinho no localStorage
    localStorage.setItem("carrinho", JSON.stringify(carrinho));

    // Dispara o evento customizado para atualizar a página
    const eventoCarrinho = new CustomEvent('atualizarCarrinho');
    document.dispatchEvent(eventoCarrinho);  // Dispara o evento para atualizar a página
});
