document.addEventListener('DOMContentLoaded', () => {
    let produtosCache = [];

    async function carregarProdutos() {
        try {
            const resposta = await fetch('./dados.json');
            if (!resposta.ok) {
                throw new Error(`Erro ao carregar JSON: ${resposta.status} ${resposta.statusText}`);
            }
            const dados = await resposta.json();
            produtosCache = dados.produtos; // Armazena os produtos em cache
            return dados.produtos;
        } catch (erro) {
            console.error('Erro ao carregar o arquivo JSON:', erro);
            return [];
        }
    }

    function gerarProdutosMaisVendidos(idAtual, quantidade = 10) {
        const containerMaisVendidos = document.querySelector('.swiper-wrapper'); // Ajustado para Swiper.js

        if (!containerMaisVendidos) {
            console.error('Elemento .swiper-wrapper não encontrado no DOM.');
            return;
        }

        // Verifica se os produtos já foram gerados
        if (containerMaisVendidos.getAttribute('data-renderizado') === 'true') return;

        if (!Array.isArray(produtosCache) || produtosCache.length === 0) {
            console.error('Nenhum produto carregado.');
            return;
        }

        const outrosProdutos = produtosCache.filter(produto => produto.id !== idAtual);
        const produtosAleatorios = outrosProdutos.sort(() => Math.random() - 0.5).slice(0, quantidade);

        containerMaisVendidos.innerHTML = ''; // Limpa o conteúdo

        produtosAleatorios.forEach(produto => {
            containerMaisVendidos.innerHTML += `
                <div class="swiper-slide"> <!-- Classe necessária para Swiper -->
                    <ul id="mais-pedidos">
                        <li>
                            <a href="produto.html?id=${produto.id}">
                                <ul class="foto-like" style="background-image: url(${produto.imagem}); background-size: cover;">
                                    
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
                            <p class="pagamento-produto">
                                Ou R$${produto.preco_credito} no cartão de crédito em até 3x sem juros
                            </p>
                        </li>
                    </ul>
                </div>            
                `;



        });

        // <a href="produto.html?id=produto1">
        //           <ul class="foto-like" style="background-image: url(IMG/camiseta-preta.jpg);">
        //             <div class="heart">
        //               <i class="fa-solid fa-heart"></i>
        //             </div>
        //           </ul>
        //         </a>
       
        // Marca como renderizado
        containerMaisVendidos.setAttribute('data-renderizado', 'true');

        new Swiper('.swiper-container', {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: '#arrow-right', // Referência ao ID do ícone da seta direita
                prevEl: '#arrow-left', // Referência ao ID do ícone da seta esquerda
            },
            breakpoints: {
                1280: { slidesPerView: 4 },
                1000: { slidesPerView: 3 },
                768: { slidesPerView: 2 },
                480: { slidesPerView: 1 },
                200: { slidesPerView: 1 },
            },
            pagination: false, // Desabilita as bolinhas de paginação
        });
        
    }

    async function inicializar() {
        const params = new URLSearchParams(window.location.search);
        const idAtual = params.get('id');
        await carregarProdutos(); // Carrega os produtos e popula o cache
        gerarProdutosMaisVendidos(idAtual);
    }

    inicializar();

    // Garante que a seção permaneça visível no redimensionamento
    window.addEventListener('resize', () => {
        const containerMaisVendidos = document.querySelector('.swiper-wrapper');
        if (containerMaisVendidos) {
            containerMaisVendidos.style.display = 'block'; // Garante que a seção esteja visível
        }
    });
});
