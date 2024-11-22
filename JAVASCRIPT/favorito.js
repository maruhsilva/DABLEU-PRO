// Função para adicionar/remover produtos dos favoritos
document.addEventListener("DOMContentLoaded", function () {
    // Carregar os favoritos ao carregar a página
    carregarFavoritos();

    // Adicionar evento de clique no ícone de coração
    const corações = document.querySelectorAll(".heart i");

    corações.forEach(coração => {
        coração.addEventListener("click", function (event) {
            event.preventDefault(); // Impede o comportamento de navegação do link

            // Obter as informações do produto
            const produtoId = this.closest("li").querySelector(".titulo-produto").textContent; // Usando o nome do produto como ID
            const produtoImagem = this.closest("li").querySelector(".foto-like").style.backgroundImage;
            const produtoLink = this.closest("a").getAttribute("href"); // Link do produto

            let favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];

            // Verificar se o produto já está nos favoritos
            const indexProduto = favoritos.findIndex(fav => fav.id === produtoId);
            if (indexProduto === -1) {
                // Adicionar aos favoritos
                favoritos.push({
                    id: produtoId,
                    nome: produtoId,
                    imagem: produtoImagem,
                    link: produtoLink // Adiciona o link do produto
                });
                this.classList.add("favorito"); // Marcar como favorito
            } else {
                // Remover dos favoritos
                favoritos.splice(indexProduto, 1);
                this.classList.remove("favorito"); // Remover o ícone de favorito
            }

            // Salvar no localStorage
            localStorage.setItem("favoritos", JSON.stringify(favoritos));
        });
    });
});

// Função para carregar os favoritos
function carregarFavoritos() {
    const favoritos = JSON.parse(localStorage.getItem("favoritos")) || [];
    
    favoritos.forEach(fav => {
        // Seleciona todos os corações e verifica se o produto está nos favoritos
        const corações = document.querySelectorAll(".heart i");
        
        corações.forEach(coração => {
            const produtoId = coração.closest("li").querySelector(".titulo-produto").textContent;

            // Se o produto estiver nos favoritos, marca o ícone como favorito
            if (produtoId === fav.id) {
                coração.classList.add("favorito");
            }
        });
    });
}
