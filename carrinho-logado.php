<?php
session_start();
session_regenerate_id();

require_once 'classes/config.php';

// Variável para exibir erros sem redirecionar
$erro = "";

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['mensagem'] = 'Sessão expirada. Por favor, faça login novamente.';
    header('Location: user-login.php');
    exit;
}

// Inicializa variáveis
$cliente = []; // Inicializando como array vazio
$metodo_pagamento = isset($_POST['metodo_pagamento']) ? htmlspecialchars($_POST['metodo_pagamento']) : "";

// Conexão com o banco de dados
if (!isset($mysqli)) {
    $erro = 'Erro de conexão com o banco de dados.';
} else {
    // Recupera os dados do cliente logado
    $id_usuario = $_SESSION['id_usuario'];
    try {
        $stmt = $mysqli->prepare("SELECT nome, cpf, telefone, endereco, numero, cep FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cliente = $result->fetch_assoc();
        } else {
            $erro = 'Usuário não encontrado. Por favor, verifique os dados.';
        }
    } catch (Exception $e) {
        error_log("Erro ao recuperar dados do cliente: " . $e->getMessage());
        $erro = 'Ocorreu um erro ao processar suas informações.';
    }
}

// Dados do cliente com valores padrão
$nome_cliente = $cliente['nome'] ?? 'Nome não encontrado';
$cpf_cliente = $cliente['cpf'] ?? 'CPF não encontrado';
$telefone_cliente = $cliente['telefone'] ?? 'Telefone não encontrado';
$endereco_cliente = $cliente['endereco'] ?? 'Endereço não encontrado';
$numero_cliente = $cliente['numero'] ?? 'Número não encontrado';
$cep_cliente = $cliente['cep'] ?? 'CEP não encontrado';

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
                  <a class="nav-link" href="cat-masc.html" role="button" >
                    MASCULINO
                  </a>
                  
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link" href="cat-fem.html" role="button">
                    FEMININO
                  </a>
                  
                </li> 
                <li class="nav-item dropdown">
                  <a class="nav-link" href="cat-kits.html" role="button">
                    KITS
                  </a>
                  
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link " href="cat-lan.html" role="button">
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
            <p class="total"><strong>Total:</strong> R$ <span id="total">0,00</span></p><br>
        </div>
        <!-- <?php if ($erro): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?> -->
        <div class="dados-cliente" id="dados">
        <form method="POST" action="processar_pagamento.php">
        <h2 style="font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; font-size: 1.3rem; padding: 0;">Dados do Cliente</h2>
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

        <h2 style="font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; font-size: 1.3rem; padding: 0;">Método de Pagamento</h2>
        <label for="metodo_pagamento">Escolha o método de pagamento:</label>
        <select name="metodo_pagamento" required>
            <option value="vazio">Selecione uma opção</option>
            <option value="cartao" <?= ($metodo_pagamento == 'cartao') ? 'selected' : '' ?>>Cartão de Crédito</option>
            <option value="pix" <?= ($metodo_pagamento == 'pix') ? 'selected' : '' ?>>Pix</option>
        </select>
        </div>

        <form action="finalizar-compra.php" method="post">
          <input type="hidden" name="carrinho" value='<?php echo json_encode($carrinho); ?>'>
          <input type="submit" value="Finalizar Compra" class="btn btn-primary">
        </form>

    </div>
    <footer>
        <div class="desenvolvedor">
            <p>© 2024 DableuPro LTDA | Todos os Direitos Reservados.</p>
            <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer">
                <img src="IMG/Sem título.png" alt="logo do desenvolvedor">
            </a>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
    <script src="JAVASCRIPT/carrinho.js"></script>
</body>
</html>
