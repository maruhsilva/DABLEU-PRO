document.addEventListener('DOMContentLoaded', () => {
    const searchIcon = document.getElementById('searchIcon');
    const buscaContainer = document.getElementById('busca');

    // Alternar visibilidade da caixa de busca com efeito de colapso
    searchIcon.addEventListener('click', (event) => {
        event.preventDefault(); // Evita comportamento padrão do link

        if (buscaContainer.classList.contains('show')) {
            // Recolher a caixa de busca
            buscaContainer.classList.remove('show');
            buscaContainer.style.maxHeight = '0';
            buscaContainer.style.padding = '0 10px'; // Apenas laterais
        } else {
            // Expandir a caixa de busca
            buscaContainer.classList.add('show');
            buscaContainer.style.maxHeight = buscaContainer.scrollHeight + 'px';
            buscaContainer.style.padding = '10px';
        }
    });

    // Fechar ao clicar fora da caixa
    document.addEventListener('click', (event) => {
        if (!buscaContainer.contains(event.target) && event.target !== searchIcon) {
            buscaContainer.classList.remove('show');
            buscaContainer.style.maxHeight = '0';
            buscaContainer.style.padding = '0 10px';
        }
    });
});
