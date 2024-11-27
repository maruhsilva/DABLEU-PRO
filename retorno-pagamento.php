<?php
// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Inclui o autoload do Mercado Pago
require __DIR__ . '/vendor/autoload.php';

// Configuração da API do Mercado Pago
MercadoPago\SDK::setAccessToken('TEST-7557293504970150-111823-b70f77389318ae03320e08bd19dd8afa-50073279');

// Recupera os parâmetros da URL (payment_id e collection_id)
$payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : null;
$collection_id = isset($_GET['collection_id']) ? $_GET['collection_id'] : null;

// Verifica se os parâmetros necessários estão presentes
if (!$payment_id || !$collection_id) {
    echo "<h1>Erro: Parâmetros de pagamento não encontrados.</h1>";
    exit;
}

try {
    // Consulta o pagamento pelo payment_id
    $payment = MercadoPago\Payment::find_by_id($payment_id);
    
    if ($payment->status === 'approved') {
        // Se o pagamento foi aprovado
        $response = [
            'success' => true,
            'message' => 'Pagamento aprovado!',
            'payment_data' => [
                'payment_id' => $payment->id,
                'amount' => $payment->transaction_details->total_paid_amount,
                'status' => $payment->status,
                'payer_email' => $payment->payer->email,
                'collection_id' => $collection_id
            ]
        ];
    } elseif ($payment->status === 'pending') {
        // Se o pagamento está pendente
        $response = [
            'success' => false,
            'message' => 'Pagamento pendente.',
            'payment_data' => [
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'payer_email' => $payment->payer->email,
                'collection_id' => $collection_id
            ]
        ];
    } else {
        // Caso o pagamento tenha falhado
        $response = [
            'success' => false,
            'message' => 'Pagamento não aprovado ou falhou.',
            'payment_data' => []
        ];
    }
} catch (Exception $e) {
    // Em caso de erro ao consultar o pagamento
    $response = [
        'success' => false,
        'message' => 'Erro ao processar a consulta do pagamento: ' . $e->getMessage(),
        'payment_data' => []
    ];
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
          <p>© 2024 DableuPro LTDA | CNPJ: XX.XXX.XXX/XXXX-XX | Rua Cliente, XXX - Jacareí - São Paulo | CEP: XX.XXX-XXX - Todos os Direitos Reservados.</p>
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
