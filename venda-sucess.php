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
      
    <section class="retorno-sucesso">
    <div class="container">
        <h1>Pagamento Realizado com Sucesso!</h1>
        <p>Parabéns, seu pagamento foi processado com sucesso. Agradecemos pela sua compra!</p>
        
        <div class="detalhes-pedido">
            <h3>Detalhes do Pedido</h3>
            <ul>
                <li><strong>ID do Pedido:</strong> 123456789</li>
                <li><strong>Valor Total:</strong> R$ 250,00</li>
                <li><strong>Status:</strong> Pago</li>
            </ul>
        </div>

        <div class="opcoes">
            <p>Você pode acompanhar o status do seu pedido em nossa plataforma. Se desejar, clique abaixo para retornar à nossa loja ou visualizar mais informações sobre o seu pedido:</p>
            <a href="index.html" class="btn btn-success">Voltar à Loja</a>
            <a href="meus-pedidos.php" class="btn btn-primary">Ver Meus Pedidos</a>
        </div>
    </div>
</section>

<!-- Estilos básicos para a página -->
<style>
    .retorno-sucesso {
        background-color: #f8f9fa;
        padding: 40px 0;
        text-align: center;
    }

    .retorno-sucesso .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .retorno-sucesso h1 {
        color: #28a745;
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .retorno-sucesso p {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .detalhes-pedido {
        background-color: #e9ecef;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 8px;
    }

    .detalhes-pedido ul {
        list-style-type: none;
        padding: 0;
    }

    .detalhes-pedido li {
        margin-bottom: 10px;
    }

    .opcoes a {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    
    .btn-success:hover {
        background-color: #218838;
    }
    
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
      <!-- <a class="top" href="">VOLTAR AO TOPO</button></a> -->

      <footer>
        <div class="container-footer">
            <div class="row-footer">
                <!-- footer col-->
                <div class="footer-col">
                  <h4>Institucional</h4>
                  <ul>
                      <li><a href="quem-somos.html">Quem somos </a></li>
                      <li><a href="nossos-servicos.html"> nossos serviços </a></li><br>
                      <!-- <li><a href=""> política de privacidade </a></li><br> -->
                      <!-- <li><a href=""> programa de afiliados</a></li> -->
                      <h4 style="margin: 0; margin-bottom: 1rem; padding: 0;">políticas</h4>
                      <li><a href="trocas.html">trocas e devoluções</a></li>
                      <li><a href="privacidade.html">termos de privacidade</a></li>
                      <li><a href="entrega.html">Prazo e formas de pagamento</a></li>
                  </ul>
              </div>
              <!--end footer col-->
              <!-- footer col-->
              <div class="footer-col">
                  <h4>Atendimento</h4>
                  <ul>
                      <li><a href="faq.html">FAQ</a></li>
                      <li><a href="contato.html">Contato</a></li>
                  </ul>
              </div>
                <!--end footer col-->
                <!-- footer col-->
                <div class="footer-col">
                    <h4>Categorias</h4>
                    <ul>
                      <li><a href="cat-masc.html">Masculino</a></li>
                      <li><a href="cat-fem.html">Feminino</a></li>
                      <li><a href="cat-kits.html">Kits</a></li>
                      <li><a href="cat-lan.html">Lançamentos</a></li>
                    </ul>
                </div>
                <!--end footer col-->
                <!-- footer col-->
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
                <!--end footer col-->
            </div>
        </div>
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
    </body>
    </html>
