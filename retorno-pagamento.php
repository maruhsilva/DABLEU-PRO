<?php
// Inicia a sessão para acessar variáveis
session_start();
require __DIR__ . '/vendor/autoload.php';
MercadoPago\SDK::setAccessToken('TEST-7557293504970150-111823-b70f77389318ae03320e08bd19dd8afa-50073279');

// Conecta ao banco de dados
$host = 'login_dableu.mysql.dbaas.com.br';
$db = 'login_dableu';
$user = 'login_dableu';
$password = 'Marua3902@';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtém os parâmetros da URL
$payment_id = $_GET['payment_id'] ?? null;
$collection_id = $_GET['collection_id'] ?? null;
$id_pedido_temp = $_GET['id_pedido_temp'] ?? null;

// Verifica os parâmetros obrigatórios
if (!$payment_id || !$collection_id || !$id_pedido_temp) {
    echo "<h1>Erro: Parâmetros de pagamento não encontrados.</h1>";
    exit;
}

try {
    // Consulta o pagamento no Mercado Pago
    $payment = MercadoPago\Payment::find_by_id($payment_id);

    // Define o status do pagamento
    $status = match ($payment->status) {
        'approved' => 'aprovado',
        'pending' => 'pendente',
        'rejected' => 'rejeitado',
        default => 'erro',
    };

    if ($status === 'aprovado') {
        // Transfere os dados para as tabelas definitivas
        $pdo->beginTransaction();

        // Copia o pedido para "pedidos_definitivo"
        $stmtPedido = $pdo->prepare("
            INSERT INTO pedidos (id_pedido, id_usuario, total, data_pedido, status, metodo_pagamento, email_payer)
            SELECT id_pedido, id_usuario, total, NOW(), :status, :metodo_pagamento, :email_payer
            FROM pedidos_temporarios
            WHERE id_pedido = :id_pedido_temp
        ");
        $stmtPedido->execute([
            'status' => $status,
            'metodo_pagamento' => $payment->payment_method_id,
            'email_payer' => $payment->payer->email,
            'id_pedido_temp' => $id_pedido_temp
        ]);

        // Copia os itens para "itens_pedido_definitivo"
        $stmtItens = $pdo->prepare("
            INSERT INTO itens_pedido_definitivo (id_pedido, nome_produto, quantidade, preco, imagem_produto)
            SELECT id_pedido, nome_produto, quantidade, preco, imagem_produto
            FROM itens_pedido
            WHERE id_pedido = :id_pedido_temp
        ");
        $stmtItens->execute(['id_pedido_temp' => $id_pedido_temp]);

        // Remove o pedido e itens temporários
        $stmtDeletePedido = $pdo->prepare("DELETE FROM pedidos_temporarios WHERE id_pedido = :id_pedido_temp");
        $stmtDeletePedido->execute(['id_pedido_temp' => $id_pedido_temp]);

        $stmtDeleteItens = $pdo->prepare("DELETE FROM itens_pedido WHERE id_pedido = :id_pedido_temp");
        $stmtDeleteItens->execute(['id_pedido_temp' => $id_pedido_temp]);

        $pdo->commit();
        echo "Pagamento aprovado e dados transferidos para as tabelas definitivas.";
    } elseif ($status === 'rejeitado') {
        // Remove os dados temporários
        $stmtDeletePedido = $pdo->prepare("DELETE FROM pedidos_temporarios WHERE id_pedido = :id_pedido_temp");
        $stmtDeletePedido->execute(['id_pedido_temp' => $id_pedido_temp]);

        $stmtDeleteItens = $pdo->prepare("DELETE FROM itens_pedido WHERE id_pedido = :id_pedido_temp");
        $stmtDeleteItens->execute(['id_pedido_temp' => $id_pedido_temp]);

        echo "Pagamento rejeitado e dados temporários removidos.";
    } else {
        echo "Pagamento pendente. Dados mantidos temporariamente.";
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao processar o pagamento: " . $e->getMessage();
}

// Esvaziar o carrinho da sessão
if (isset($_SESSION['carrinho'])) {
    unset($_SESSION['carrinho']);
}

// Incluir código JavaScript para esvaziar o carrinho no localStorage
echo "
<script>
    localStorage.removeItem('carrinho');  // Limpa o carrinho do localStorage
</script>
";

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
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999; display: flex; align-items: center;">
      <div class="container-fluid justify-content-center nav-container" style="gap: 5rem;">
          <a class="navbar-brand" href="index.html">
              <img src="IMG/NOME 10cm COM R.png" alt="logo da empresa" style="width: 7rem; padding-bottom: .2rem;">
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
    <style>
        /* body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
        } */
        h1 {
            color: #333;
        }
        .payment-info {
            background-color: rgba(100%, 100%, 100%, .4);
            padding: 40px 100px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            text-align: left;
        }
        .payment-info p {
            margin: 10px 0;
        }
        .success {
            color: green;
        }
        .failure {
            color: red;
        }
        .pending {
            color: orange;
        }

        .opcoes a {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    
    .btn-success:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #ffc107;
        color: white;
    }

    .status {
       display: flex;
       align-items: center;
       justify-content: center;
       flex-direction: column;
       padding-top: 5rem;
       padding-bottom: 5rem;
       max-width: 800px;
       margin-inline: auto;
       text-align: center;
    }
    
    .btn-warning:hover {
      background-color: #e0a800;
    }

    @media (max-width: 800px) {
        .status {
            padding: 2rem;
        }

        .payment-info {
            padding: 2rem 0;
            text-align: center;
        }
    }
    </style>
    <div class="status">
        <?php if ($response['success']): ?>
        <h1 class="success">Pagamento Aprovado!</h1><br>
        <div class="payment-info">
            <p style="font-size: 1.5rem; text-align: center; padding-bottom: 1rem;"><strong>Detalhes do Pedido</strong></p>
            <p><strong>ID do Pagamento:</strong> <?php echo $response['payment_data']['payment_id']; ?></p>
            <p><strong>Valor Pago:</strong> R$ <?php echo number_format($response['payment_data']['amount'], 2, ',', '.'); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($response['payment_data']['status']); ?></p>
            <p><strong>E-mail do Pagador:</strong> <?php echo $response['payment_data']['payer_email']; ?></p>
        </div><br>
        <div class="opcoes">
                <p>Você pode acompanhar o status do seu pedido em nossa plataforma. Se desejar, clique abaixo para retornar à nossa loja ou visualizar mais informações sobre o seu pedido:</p>
                <a href="index.html" class="btn btn-success">Voltar à Loja</a>
                <a href="meus-pedidos.php" class="btn btn-primary">Ver Meus Pedidos</a>
        </div>
    </div>
    <?php elseif ($response['message'] === 'Pagamento pendente.'): ?>
    <h1 class="pending">Pagamento Pendente!</h1><br>
    <div class="payment-info">
        <p style="font-size: 1.5rem; text-align: center; padding-bottom: 1rem;"><strong>Detalhes do Pedido</strong></p>
        <p><strong>ID do Pagamento:</strong> <?php echo $response['payment_data']['payment_id']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($response['payment_data']['status']); ?></p>
        <p><strong>E-mail do Pagador:</strong> <?php echo $response['payment_data']['payer_email']; ?></p>
    </div>
    <div class="opcoes">
            <p>Você pode acompanhar o status do seu pedido em nossa plataforma. <br> Se desejar, clique abaixo para retornar à nossa loja ou visualizar mais informações sobre o seu pedido:</p>
            <a href="index.html" class="btn btn-warning">Voltar à Loja</a>
            <a href="meus-pedidos.php" class="btn btn-primary">Ver Meus Pedidos</a>
        </div>
    <?php else: ?>
    <h1 class="failure">Pagamento Não Aprovado!</h1><br>
    <div class="payment-info">
        <p style="font-size: 1.5rem; text-align: center; padding-bottom: 1rem;"><strong>Detalhes do Pedido</strong></p>
        <p><strong>Erro:</strong> <?php echo $response['message']; ?></p>
    </div>
    <div class="opcoes">
            <p>Você pode acompanhar o status do seu pedido em nossa plataforma. <br> Se desejar, clique abaixo para retornar à nossa loja ou visualizar mais informações sobre o seu pedido:</p>
            <a href="index.html" class="btn btn-danger">Voltar à Loja</a>
            <a href="meus-pedidos.php" class="btn btn-primary">Ver Meus Pedidos</a>
        </div>
    <?php endif; ?>

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
                      <li><a href="cat-masc.html">Masculino</a></li>
                      <li><a href="cat-fem.html">Feminino</a></li>
                      <li><a href="cat-kits.html">Kits</a></li>
                      <li><a href="cat-lan.html">Lançamentos</a></li>
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
          <p>© 2024 DableuPro LTDA | Jacareí - São Paulo | Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.png" alt="logo do desenvolvedor"></a>
        </div>
    </footer>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
      <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
      <script src="https://kit.fontawesome.com/43b36f20b7.js" crossorigin="anonymous"></script>
      <script src="JAVASCRIPT/slick.min.js"></script>
      <script src="JAVASCRIPT/slick.js"></script>
      <script src="JAVASCRIPT/funcoes.js"></script>
      <script src="JAVASCRIPT/addcarrinho.js"></script>
      <script src="JAVASCRIPT/carrinho.js"></script>
      
    </body>
    </html>
