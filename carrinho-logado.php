<?php
session_start();
include "config.php"; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: user-login.php"); // Redireciona para a página de login, se necessário
    exit;
}

// Recupera os dados do cliente com base no ID do usuário logado
$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT nome, cpf, telefone, endereco, numero, cep FROM usuarios WHERE id_usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se os dados foram encontrados
if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();

    // Atribui os valores para exibição no formulário
    $nome_cliente = $cliente['nome'];
    $cpf_cliente = $cliente['cpf'];
    $telefone_cliente = $cliente['telefone'];
    $endereco_cliente = $cliente['endereco'];
    $numero_cliente = $cliente['numero'];
    $cep_cliente = $cliente['cep'];
} else {
    // Caso os dados do cliente não sejam encontrados
    $nome_cliente = "N/A";
    $cpf_cliente = "N/A";
    $telefone_cliente = "N/A";
    $endereco_cliente = "N/A";
    $numero_cliente = "N/A";
    $cep_cliente = "N/A";
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dableu Pro - Moda Fitness Masculina e Feminina</title>
    <link rel="shortcut icon" type="imagex/png" href="IMG/BARRAS-PRETAS-4cm-6cm-_2_.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/slick-theme.css">
    <link rel="stylesheet" href="CSS/slick.css">
    <link rel="stylesheet" href="CSS/carrinho.css">
</head>
<body>
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999; display: flex; align-items: center;">
      <div class="container-fluid justify-content-center nav-container" style="gap: 5rem;">
          <a class="navbar-brand" href="index.html">
              <img src="IMG/NOME 10cm COM R.webp" alt="logo da empresa" style="width: 7rem; padding-bottom: .2rem;">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse flex-grow-0" id="navbarNavDropdown" style="font-size: 1.05rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
              <ul class="navbar-nav" style="gap: 2rem; display: flex; align-items: center;">
                  <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="index.html">HOME</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="cat-masc.html" role="button">MASCULINO</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="cat-fem.html" role="button">FEMININO</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="cat-kits.html" role="button">KITS</a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link" href="cat-lan.html" role="button">LANÇAMENTOS</a>
                  </li>
                  <ul class="icons">
                      <li>
                          <a href="user-logado.php" style="font-size: 1.5rem;"><i class="bi bi-person"></i></a>
                          <!-- <a href="#busca"><i class="bi bi-search"></i></a> -->
                          <a href="favoritos.html"><i class="bi bi-heart"></i></a>
                          <a href="carrinho.html">
                              <div id="cart-icon-container"></div>
                          </a>
                      </li>
                  </ul>
              </ul>
          </div>
      </div>
  </nav>

    <style>
/* Estilo padrão da barra de navegação */
.nav-container {
    display: flex;
    align-items: center;
    justify-content: center; /* Alinhamento centralizado em telas maiores */
    gap: 5rem; /* Espaçamento padrão */
}

/* Ajuste para telas menores */
@media (max-width: 800px) {
    .nav-container {
        justify-content: space-between; /* Distribui espaço entre logo e botão */
        gap: 0; /* Remove espaçamento extra */
    }

    /* Logo alinhado à esquerda */
    .navbar-brand {
        margin-right: auto; /* Empurra o logo para a esquerda */
    }

    /* Botão de hambúrguer alinhado à direita */
    .navbar-toggler {
        margin-left: auto; /* Empurra o botão para a direita */
    }
}
      </style>
    <div class="container">
        <h1>Verificação dos Produtos</h1>
        <table id="tabela-carrinho" class="tabela-carrinho">
            <thead>
              <tr>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Tamanho</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Valor Total</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="produtos-carrinho">
                <!-- Os produtos serão inseridos aqui dinamicamente -->
            </tbody>
        </table>
        <div id="mensagem-carrinho-vazio" style="display:none; color: black; text-align: center;">
          <h2 style="font-size: 1.3rem;">O seu carrinho está vazio!</h2>
          <p>Adicione produtos ao carrinho para começar a comprar.</p>
        </div>
        <div class="resumo">
            <p><strong>Subtotal:</strong> R$ <span id="subtotal">0,00</span></p>
            <p><strong>Frete:</strong> R$ <span id="frete">0,00</span></p>
            <p class="total"><strong>Total:</strong> R$ <span id="total-credito">0,00</span></p>
            <p class="total"><strong>Total Pix:</strong> R$ <span id="total-pix">0,00</span></p>
        </div>
        <div class="dados-cliente" id="dados">
            <form method="POST" action="">
                <h2 style="font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; font-size: 1.3rem; padding: 0;">Dados do Cliente:</h2>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?= $nome_cliente ?>" readonly>

                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" value="<?= $cpf_cliente ?>" readonly>

                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" value="<?= $telefone_cliente ?>" readonly>

                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" value="<?= $endereco_cliente ?>" readonly>

                <label for="numero">Número:</label>
                <input type="text" name="numero" value="<?= $numero_cliente ?>" readonly>

                <label for="cep">CEP:</label>
                <input type="text" name="cep" value="<?= $cep_cliente ?>" readonly><br><br>

                <!-- Opções de pagamento -->
                <div>
                    <label for="metodo_pagamento">Método de Pagamento:</label><br>
                    <select id="metodo_pagamento">
                        <option value="credito">Crédito</option>
                        <option value="pix">Pix/Boleto</option>
                    </select>
                </div>
            </form>
        </div><br>
      

      <button id="finalizar-compra-btn" class="btn btn-dark">Finalizar Compra</button>

      <script>
document.getElementById('finalizar-compra-btn').addEventListener('click', function () {
    const metodoPagamento = document.getElementById('metodo_pagamento').value;
    const carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];
    
    // Preparar os itens para enviar ao backend
    const itens = carrinho.map(item => ({
        title: item.nome,
        quantity: item.quantidade,
        unit_price: metodoPagamento === 'pix' ? item.preco - 4 : item.preco  // Desconto para Pix
    }));

    // Dados a serem enviados para o servidor
    const dados = {
        itens: itens,
        metodo_pagamento: metodoPagamento  // Envia o método de pagamento escolhido
    };

    fetch('processar_pagamento.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(dados),
})
.then(response => response.text())  // Usamos .text() para pegar a resposta como texto
.then(text => {
    console.log('Resposta recebida do servidor:', text);  // Verifique o conteúdo
    try {
        const data = JSON.parse(text);  // Tenta parsear o JSON
        if (data.success) {
            window.location.href = data.redirect_url;  // Redireciona para o Mercado Pago
        } else {
            alert('Erro: ' + data.message);
        }
    } catch (error) {
        console.error('Erro ao parsear JSON:', error);
    }
})
.catch(error => console.error('Erro ao processar o pagamento:', error));
});


</script>

      <footer>
      <div class="desenvolvedor">
          <p>© 2024 DableuPro LTDA | Jacareí - São Paulo | Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.webp" alt="logo do desenvolvedor"></a>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
    <script src="JAVASCRIPT/carrinho.js"></script>
    <!-- <script src="JAVASCRIPT/carrinho-logado.js"></script> -->
    <!-- <script src="JAVASCRIPT/finalizar-compra.js"></script> -->
    <!-- <script src="JAVASCRIPT/miniCart.js"></script> -->
</body>
</html>