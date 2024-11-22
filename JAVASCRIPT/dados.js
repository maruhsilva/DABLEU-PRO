
// Obtem o ID do produto da URL
const params = new URLSearchParams(window.location.search);
const productId = params.get('id');

// Exemplo: carregar dados do produto
console.log(productId); // Exibe '123'