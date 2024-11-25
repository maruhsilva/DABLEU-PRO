<?php
session_start();
session_regenerate_id();
require_once 'classes/config.php'; // Inclui o arquivo de configuração para o banco de dados

// if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['metodo_pagamento'])) {
//   // Redireciona de volta para a página do carrinho
//   header("Location: processar_pagamento.php");
//   exit;
// }

// Verifica se o carrinho foi enviado e possui produtos
if (isset($_POST["carrinho"]) && !empty(json_decode($_POST["carrinho"], true))) {
  // Exibe o carrinho na página e mantém o usuário
  $carrinho = json_decode($_POST["carrinho"], true);
  // Mostra o conteúdo do carrinho, mas não redireciona automaticamente
} else {
  echo "Seu carrinho está vazio.";
}

// Obtém os dados do cliente a partir do banco de dados
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nome, cpf, telefone, endereco, numero, cep FROM usuarios WHERE id_usuario = '$id_usuario'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Se o usuário existe, preenche as variáveis com os dados do banco
    $cliente = $result->fetch_assoc();
    $nome_cliente = $cliente['nome'];
    $cpf_cliente = $cliente['cpf'];
    $telefone_cliente = $cliente['telefone'];
    $endereco_cliente = $cliente['endereco'];
    $numero_cliente = $cliente['numero'];
    $cep_cliente = $cliente['cep'];
} else {
    // Caso o cliente não seja encontrado, pode exibir uma mensagem de erro ou redirecionar
    $_SESSION['mensagem'] = 'Erro ao recuperar os dados do cliente.';
    header('Location: carrinho-logado.php');
    exit;
}

// Se o método de pagamento já foi escolhido, podemos prosseguir
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dableu Pro - Moda Fitness Masculina e Feminina</title>
    <link rel="shortcut icon" type="imagex/png" href="IMG/BARRAS-PRETAS-4cm-6cm-_2_.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="CSS/slick-theme.css">
    <link rel="stylesheet" href="CSS/slick.css">
    <link rel="stylesheet" href="CSS/carrinho.css">
</head>
<body>
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999;  display: flex; align-items: center;">
      <div class="container-fluid justify-content-center" style="gap: 5rem;">
        <a class="navbar-brand" href="index.html"><img src="IMG/NOME 8cm - BRANCO E PRETO (2).png" alt="logo da empresa" style="width: 10rem; padding-bottom: .2rem;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
          <div class="collapse navbar-collapse flex-grow-0" id="navbarNavDropdown" style="font-size: 1.05rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
              <ul class="navbar-nav" style="gap: 2rem; display: flex; align-items: center;">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="index.html">HOME</a>
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    MASCULINO
                  </a>
                  
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    FEMININO
                  </a>
                  
                </li> 
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    KITS
                  </a>
                  
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    LANÇAMENTOS
                  </a>
                  
                </li>
                <ul class="icons">
                  <li>
                    <a href="user-logado.php" style="font-size: 1.5rem;"><i class="bi bi-person"></i></a>
                    <a href="#busca"><i class="bi bi-search"></i></a>
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
      <div class="container">
        <h1>Verificação dos Produtos</h1>
              
        <!-- Tabela para exibir os produtos -->
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
            <p class="total"><strong>Total:</strong> R$ <span id="total">0,00</span></p>
        </div>

        

        <form method="POST" action="processar_pagamento.php">
    <h2>Dados do Cliente</h2>
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
    <input type="text" name="cep" value="<?= $cep_cliente ?>" readonly>

    <h2>Método de Pagamento</h2>
    <label for="metodo_pagamento">Escolha o método de pagamento:</label>
    <select name="metodo_pagamento" required>
        <option value="cartao" <?= ($metodo_pagamento == 'cartao') ? 'selected' : '' ?>>Cartão de Crédito</option>
        <option value="pix" <?= ($metodo_pagamento == 'pix') ? 'selected' : '' ?>>Pix</option>
    </select>

    <div class="botao-finalizar">
    <button>Finalizar Compra</button>
</div>
    
    </div>

      <!-- <a class="top" href="">VOLTAR AO TOPO</button></a> -->

      <footer>
        <!-- <div class="container-footer">
            <div class="row-footer">
                
                <div class="footer-col">
                  <h4>Institucional</h4>
                  <ul>
                      <li><a href="quem-somos.html">Quem somos </a></li>
                      <li><a href="nossos-servicos.html"> nossos serviços </a></li><br>
                      
                      <h4 style="margin: 0; margin-bottom: 1rem; padding: 0;">políticas</h4>
                      <li><a href="trocas.html">trocas e devoluções</a></li>
                      <li><a href="privacidade.html">termos de privacidade</a></li>
                      <li><a href="entrega.html">Prazo e formas de pagamento</a></li>
                  </ul>
              </div>
              
              
              <div class="footer-col">
                  <h4>Atendimento</h4>
                  <ul>
                      <li><a href="faq.html">FAQ</a></li>
                      <li><a href="contato.html">Contato</a></li>
                  </ul>
              </div>
                
                
                <div class="footer-col">
                    <h4>Categorias</h4>
                    <ul>
                        <li><a href="#">Camisetas</a></li>
                        <li><a href="#">Bermudas</a></li>
                        <li><a href="#">Calças</a></li>
                        <li><a href="#">Acessórios</a></li>
                    </ul>
                </div>
                
                
                <div class="footer-col">
                    <h4>Se inscreva!</h4>
                    <div class="form-sub">
                        <form>
                            <input type="email" placeholder="Digite o seu e-mail" required>
                            <button>Inscrever</button>
                        </form>
                    </div>

                    <div class="medias-socias">
                        <a href="#"> <i class="fa fa-facebook"></i> </a>
                        <a href="#"> <i class="fa fa-instagram"></i> </a>
                        <a href="#"> <i class="fa fa-twitter"></i> </a>
                        <a href="#"> <i class="fa fa-linkedin"></i> </a>
                    </div>

                </div>
                
            </div>
        </div> -->
        <div class="desenvolvedor">
          <p>© 2024 DableuPro LTDA | CNPJ: XX.XXX.XXX/XXXX-XX | Rua Cliente, XXX - Jacareí - São Paulo | CEP: XX.XXX-XXX - Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.png" alt="logo do desenvolvedor"></a>
        </div>
    </footer>
      <script src="https://sdk.mercadopago.com/js/v2"></script>
      <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
      <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
      <script src="https://kit.fontawesome.com/43b36f20b7.js" crossorigin="anonymous"></script>
      <script src="JAVASCRIPT/slick.min.js"></script>
      <script src="JAVASCRIPT/slick.js"></script>
      <script src="JAVASCRIPT/miniCart.js"></script>
      <script src="JAVASCRIPT/carrinho.js"></script>
      <script src="JAVASCRIPT/addcarrinho.js"></script>
      <script src="JAVASCRIPT/carrinho-logado.js"></script>
    </body>
</html>