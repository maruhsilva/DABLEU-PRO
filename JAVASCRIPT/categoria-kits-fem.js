// Função para carregar e exibir produtos filtrados do JSON
function carregarProdutos() {
    fetch('dados.json')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('produtos-container7');
            container.innerHTML = ''; // Limpa o container antes de renderizar

            // Filtrar os produtos pela categoria Masculino e subcategoria Camisetas
            const produtosFiltrados = data.produtos.filter(produto => 
                produto.categoria === 'Feminino' && produto.subcategoria === 'Kits'
            );

            // Verificar se há produtos para exibir
            if (produtosFiltrados.length === 0) {
                container.innerHTML = `<p>Nenhum produto encontrado na categoria "Feminino" e subcategoria "Kits".</p>`;
                return;
            }

            // Embaralhar os produtos filtrados
            const produtosEmbaralhados = produtosFiltrados.sort(() => Math.random() - 0.5);

            // Iterar pelos produtos embaralhados e criar os cards
            produtosEmbaralhados.forEach(produto => {
                const card = document.createElement('div');
                card.classList.add('produto-card');

                // Montar o HTML do card
                card.innerHTML = `
                    <ul>
                        <li>
                            <a href="produto.html?id=${produto.id}">
                                <ul class="foto-like" style="background-image: url(${produto.imagem_principal}); background-size: cover;">
                                    <div class="heart"><i class="bi bi-heart"></i></div>
                                </ul>
                            </a>
                            <a href="produto.html?id=${produto.id}">
                                <p class="titulo-produto">${produto.nome}</p>
                            </a>
                            <div class="precos">
                                <p class="preco-produto-credito">R$${produto.preco_credito}</p>
                                <p class="preco-produto-pix">R$${produto.preco_pix}</p>
                                <p class="preco-pix">(NO PIX)</p>
                            </div>
                            <p class="pagamento-produto">Ou R$${produto.preco_credito} no cartão de crédito em até 3x sem juros</p>
                        </li>
                    </ul>
                `;

                // Adicionar o card ao container
                container.appendChild(card);
            });
        })
        .catch(error => console.error('Erro ao carregar os produtos:', error));
}

// Carregar produtos ao iniciar a página
document.addEventListener('DOMContentLoaded', carregarProdutos);