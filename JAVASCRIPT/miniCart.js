document.addEventListener("DOMContentLoaded", function () {
    // Função para adicionar produto ao carrinho
    const botaoAdicionarCarrinho = document.querySelector(".add-to-cart");

    if (botaoAdicionarCarrinho) {
        botaoAdicionarCarrinho.addEventListener("click", function () {
            const imagemProduto = document.querySelector(".imagem-produto")?.src || '';
            const nomeProduto = document.querySelector(".titulo-produto")?.textContent.trim() || '';
            const tamanhoSelecionado = document.querySelector(".tamanho li.selected")?.textContent.trim() || '';
            const precoProduto = parseFloat(document.querySelector(".preco-produto-pix")?.textContent.replace("R$", "").replace(",", ".").trim() || 0);
            const quantidadeProduto = parseInt(document.querySelector("#quantia")?.value || 1);

            // Verifica se o tamanho foi selecionado
            if (!tamanhoSelecionado) {
                alert("Por favor, selecione o tamanho do produto.");
                return;
            }

            // Verifica se os dados são válidos
            if (nomeProduto && precoProduto > 0 && quantidadeProduto > 0) {
                const produto = {
                    nome: nomeProduto,
                    tamanho: tamanhoSelecionado,
                    preco: precoProduto,
                    quantidade: quantidadeProduto,
                    imagem: imagemProduto,
                };

                // Atualiza o carrinho no localStorage
                let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
                const indexProdutoExistente = carrinho.findIndex(
                    (item) => item.nome === produto.nome && item.tamanho === produto.tamanho
                );

                if (indexProdutoExistente >= 0) {
                    carrinho[indexProdutoExistente].quantidade += produto.quantidade;
                } else {
                    carrinho.push(produto);
                }

                localStorage.setItem("carrinho", JSON.stringify(carrinho));
                alert("Produto adicionado ao carrinho com sucesso!");
            } else {
                alert("Por favor, preencha todos os campos corretamente.");
            }
        });
    }

    // Seleciona o tamanho do produto
    document.querySelectorAll(".tamanho li").forEach(function (tamanho) {
        tamanho.addEventListener("click", function () {
            document.querySelectorAll(".tamanho li").forEach(function (item) {
                item.classList.remove("selected");
            });
            this.classList.add("selected");
        });
    });

    // Função para alterar a quantidade do produto
    const inputQuantidade = document.querySelector("#quantia");

    if (inputQuantidade) {
        // Botão para aumentar a quantidade
        const botaoAumentar = document.querySelector("#minus");
        const botaoDiminuir = document.querySelector("#plus");

        if (botaoAumentar) {
            botaoAumentar.addEventListener("click", function () {
                let quantidade = parseInt(inputQuantidade.value) || 1;
                quantidade += 1;
                inputQuantidade.value = quantidade;
            });
        }

        if (botaoDiminuir) {
            botaoDiminuir.addEventListener("click", function () {
                let quantidade = parseInt(inputQuantidade.value) || 1;
                quantidade -= 1;
                if (quantidade < 1) quantidade = 1; // Garante que a quantidade nunca será menor que 1
                inputQuantidade.value = quantidade;
            });
        }
    }
});
