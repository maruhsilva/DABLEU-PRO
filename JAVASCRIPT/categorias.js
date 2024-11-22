document.addEventListener('DOMContentLoaded', () => {
    let categoriasCache = [];

    async function carregarCategorias() {
        try {
            const resposta = await fetch('./dados.json');
            if (!resposta.ok) {
                throw new Error(`Erro ao carregar JSON: ${resposta.status} ${resposta.statusText}`);
            }
            const dados = await resposta.json();
            categoriasCache = dados.categorias; // Armazena as categorias em cache
            return dados.categorias;
        } catch (erro) {
            console.error('Erro ao carregar o arquivo JSON:', erro);
            return [];
        }
    }

    function gerarCategorias(categoriaFiltrada = null) {
        const containerCategorias = document.querySelector('#categorias-container');
        containerCategorias.innerHTML = ''; // Limpa o conteúdo atual

        const categoriasParaExibir = categoriaFiltrada ? categoriasCache.filter(c => c.nome === categoriaFiltrada) : categoriasCache;

        categoriasParaExibir.forEach(categoria => {
            categoria.itens.forEach(item => {
                const categoriaDiv = document.createElement('div');
                categoriaDiv.classList.add('categoria');
                categoriaDiv.innerHTML = `
                    <img src="${item.imagem}" alt="${item.nome}">
                    <h3>${item.nome}</h3>
                `;
                categoriaDiv.addEventListener('click', () => {
                    window.location.href = item.url;
                });
                containerCategorias.appendChild(categoriaDiv);
            });
        });
    }

    function adicionarFiltros() {
        const filtros = document.querySelectorAll('.filtro');
        filtros.forEach(filtro => {
            filtro.addEventListener('click', () => {
                const categoriaFiltrada = filtro.getAttribute('data-categoria');
                gerarCategorias(categoriaFiltrada);
            });
        });
    }

    async function inicializar() {
        await carregarCategorias(); // Carrega as categorias e popula o cache
        gerarCategorias(); // Gera as categorias na tela
        adicionarFiltros(); // Adiciona funcionalidade aos filtros
    }

    inicializar();
});
