<?php
// Conexão ao banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=loja_virtual', 'root', '');

// ID do produto (obtido via URL ou lógica)
$id_produto = $_GET['id'] ?? 1;

// Consulta o produto
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
$stmt->execute([$id_produto]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado!");
}

// Consulta imagens adicionais
$stmt = $pdo->prepare("SELECT * FROM imagens_produto WHERE id_produto = ? ORDER BY ordem");
$stmt->execute([$id_produto]);
$imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta tamanhos
$stmt = $pdo->prepare("SELECT * FROM tamanhos WHERE id_produto = ?");
$stmt->execute([$id_produto]);
$tamanhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dableu Pro - Moda Fitness Masculina e Feminina</title>
    <link rel="shortcut icon" type="imagex/png" href="IMG/BARRAS-PRETAS-4cm-6cm-_2_.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="CSS/slick-theme.css">
    <link rel="stylesheet" href="CSS/slick.css">
    <link rel="stylesheet" href="CSS/produto1.css">

    
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
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    MASCULINO
                  </a>
                  <ul class="dropdown-menu" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Produtos</h6></li>
                    <li><a class="dropdown-item" href="#">Camisetas</a></li>
                    <li><a class="dropdown-item" href="#">Regatas</a></li>
                    <li><a class="dropdown-item" href="#">Shorts e Bermudas</a></li><br>
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Acessórios</h6></li>  
                    <li><a class="dropdown-item" href="#">Cuecas</a></li>
                    <li><a class="dropdown-item" href="#">Meias</a></li>
                    <li><a class="dropdown-item" href="#">Bonés</a></li>
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    FEMININO
                  </a>
                  <ul class="dropdown-menu" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Produtos</h6></li>  
                    <li><a class="dropdown-item" href="#">Camisetas/Cropped</a></li>
                    <li><a class="dropdown-item" href="#">Calça/Legging</a></li>
                    <li><a class="dropdown-item" href="#">Shorts</a></li><br>
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Acessórios</h6></li>  
                    <li><a class="dropdown-item" href="#">Top</a></li>
                    <li><a class="dropdown-item" href="#">Meias</a></li>
                    <!-- <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                  </ul>
                </li> 
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    KITS
                  </a>
                  <ul class="dropdown-menu" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Masculinos</h6></li>
                    <li><a class="dropdown-item" href="#">Camisetas</a></li>
                    <li><a class="dropdown-item" href="#">Camisetas + Bermudas</a></li>
                    <li><a class="dropdown-item" href="#">Acessórios</a></li><br>
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Femininos</h6></li>
                    <li><a class="dropdown-item" href="#">Camisetas</a></li>
                    <li><a class="dropdown-item" href="#">Camisetas + Shorts</a></li>
                    <li><a class="dropdown-item" href="#">Acessórios</a></li><br>
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    LANÇAMENTOS
                  </a>
                  <ul class="dropdown-menu" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                    <li><h6 class="dropdown-header" style="font-size: 1.05rem;">Produtos</h6></li>
                    <li><a class="dropdown-item" href="#">Camisetas</a></li>
                    <li><a class="dropdown-item" href="#">Bermudas/Calças</a></li>
                    <li><a class="dropdown-item" href="#">Acessórios</a></li>
                  </ul>
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

    <section class="geral-produto">
    <div class="geral-info">
        <div class="produto">
            <div class="col-12 col-lg-6" id="productGallery">
                <script>
                    function imageClick(imageNumber) {
                        setTimeout(() => {
                            const sliderElement = document.getElementById('pgalleryModal');
                            swiffyslider.slideTo(sliderElement, imageNumber);
                            swiffyslider.onSlideEnd(sliderElement, () => sliderElement.querySelector(".slider-container").focus());
                        }, 300)
                    }

                    function thumbHover(imageNumber) {
                        const sliderElement = document.getElementById('pgallery');
                        swiffyslider.slideTo(sliderElement, imageNumber);
                    }
                </script>
                <div class="swiffy-slider slider-item-ratio slider-item-ratio-1x1 slider-nav-round slider-nav-nodelay" id="pgallery">
                    <ul class="slider-container">
                        <?php foreach ($productImages as $index => $image): ?>
                            <li>
                                <img src="<?= $image ?>" loading="lazy" alt="Imagem do produto" 
                                     data-bs-toggle="modal" data-bs-target="#productGalleryModal" 
                                     onclick="imageClick(<?= $index ?>)">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="slider-nav" aria-label="Go previous"></button>
                    <button type="button" class="slider-nav slider-nav-next" aria-label="Go next"></button>
                </div>
                <div class="swiffy-slider slider-nav-dark slider-nav-sm slider-nav-chevron slider-item-show4 slider-item-snapstart slider-item-ratio slider-item-ratio-1x1 slider-nav-visible slider-nav-page slider-nav-outside-expand pt-3 d-none d-lg-block">
                    <ul class="slider-container" id="pgallerythumbs" style="cursor:pointer">
                        <?php foreach ($productImages as $index => $image): ?>
                            <li>
                                <img src="<?= $image ?>" loading="lazy" alt="Thumbnail do produto" onmouseover="thumbHover(<?= $index ?>)">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="slider-nav" aria-label="Go previous"></button>
                    <button type="button" class="slider-nav slider-nav-next" aria-label="Go next"></button>
                </div>
                <div class="modal fade" id="productGalleryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 99999;">
                    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                        <div class="modal-content" style="background-color: transparent;">
                            <div class="modal-body" style="display: flex; align-items: center; justify-content: center;">
                                <div class="swiffy-slider w-50 h-70 slider-nav-dark" id="pgalleryModal">
                                    <ul class="slider-container" tabindex="-1">
                                        <?php foreach ($productImages as $image): ?>
                                            <li class="d-flex align-items-center justify-content-center">
                                                <img src="<?= $image ?>" loading="lazy" class="mw-100 mh-100" alt="Imagem do produto ampliada" data-bs-dismiss="modal">
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="button" class="slider-nav" aria-label="Go previous"></button>
                                    <button type="button" class="slider-nav slider-nav-next" aria-label="Go next"></button>
                                    <ul class="slider-indicators slider-indicators-dark slider-indicators-highlight slider-indicators-round">
                                        <?php foreach ($productImages as $index => $_): ?>
                                            <li class="<?= $index === 0 ? 'active' : '' ?>"></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                    <?= $productName ?>
                </b><br>
                <?= nl2br($productDetails) ?>
            </div>
            <img class="details" src="<?= $productDetailImage ?>" alt="Detalhes do produto" style="width: 100%; height: 400px; object-fit: cover;">
            <!-- <div class="medidas">
                <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">TABELA DE MEDIDAS:</b>
                <img class="medidas" src="<?= $sizeTableImage ?>" alt="Tabela de medidas" style="width: 100%; height: 400px;">
            </div> -->
        </div>
    </div>
    <div class="geral-preco">
        <div class="informacoes">
            <p class="titulo-produto"><?= $productName ?></p>
            <div class="precos">
                <p class="preco-produto-credito">R$<?= $productPriceCredit ?></p>
                <p class="preco-produto-pix">R$<?= $productPricePix ?></p>
                <p class="preco-pix">(NO PIX)</p>
            </div>
            <p class="pagamento-produto">
                Ou R$<?= $productPriceCredit ?> no cartão de crédito <br> em até 2x sem juros
            </p>
            <p>Cor selecionada: <b><?= strtoupper($selectedColor) ?></b></p>
            <img src="<?= $selectedColorImage ?>" class="imagem-produto" alt="Cor do produto" style="width: 80px; border: 2px solid black;">
            <p style="padding-top: 1rem;">Selecione o tamanho:</p>
            <ul class="tamanho">
                <?php foreach ($sizes as $size): ?>
                    <li onclick="selecionarTamanho(this)"><?= $size ?></li>
                <?php endforeach; ?>
            </ul>
            <p style="padding-top: 1rem;">Quantidade:</p>
            <div id="quantidade" class="quantidade">
                <input type="button" id="plus" value='-' onclick="alterarQuantidade(-1)">
                <input id="quantia" name="quant" class="text" size="1" type="text" value="1" maxlength="5">
                <input type="button" id="minus" value='+' onclick="alterarQuantidade(1)">
            </div><br>
            <input type="text" id="cep" placeholder="Calcule o Frete" oninput="calcularFrete()">
            <p id="valorFrete" style="display:none;">Valor do Frete: R$0,00</p>
            <div class="button">
                <button type="button" class="button-hover-background add-to-cart" onclick="adicionarCarrinho()">ADICIONAR AO CARRINHO</button>
            </div>
            <div id="modal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <p>Produto adicionado ao carrinho!</p>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="content">
              <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Tshirt Dry Fit Black</b> <br>
              • Tecido: Dry Fit <br>
              • Modelagem: Raglan <br>
              • Silk: Halteres e Marca <br>
              • Modelo veste G <br>
              • Composição: 100% Poliéster <br><br>
    
              <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">TECIDO DRY FIT</b> <br>
              O tecido dry fit é a tecnologia ideal para treinos na academia, crossfit e outros esportes. Com ele você treina com conforto e segurança, mantendo a temperatura corporal ideal.
              <br><br>
              <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">MODELAGEM</b> <br>
              As camisetas Raglan são projetadas para proporcionar um conforto máximo, já que os cortes das mangas são feitos para permitir mais movimento e menos restrição de movimentos. Isso é especialmente útil para pessoas que praticam esportes ou outras atividades físicas.
              <br><br>
              <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">TECNOLOGIA</b> <br>
              O tecido Dryfit é altamente respirável, o que permite que o ar circule livremente através da roupa. Isso ajuda a manter a temperatura corporal regulada, evitando o superaquecimento e mantendo o corpo fresco.
            </div>
            <img class="details" src="IMG/Untitled.png" alt="detalhes da camiseta" style="width: 100%; height: 400px; object-fit: cover;">
            <div class="content1">
              <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">CAMISETA DE ACADEMIA DRY FIT</b> <br>
              <b style="font-size: 1.4rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">IDEAL PARA SUA PERFORMANCE</b>
              <br><br>
              Não importa a sua atividade, essa camiseta tem a funcionalidade de aumentar a sua performance nos treinos de alta e baixa intensidade. Prepare-se para um treino como você nunca viu.
              <br>
            </div>
            <div class="medidas">
              <b style="font-size: 1.6rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">TABELA DE MEDIDAS:</b>
              <img class="medidas" src="IMG/Sem título-3_Prancheta 1.jpg" alt="" style="width: 100%; height: 400px;">
            </div>
          </div>
        </div>

    <section class="dicas">
        <!-- <p>COMPRE TAMBÉM</p> -->
        <div class="mais-pedidos">
          <p>COMPRE TAMBÉM</p>
          <div class="arrow">
            <i id="arrow-left" class="fa-solid fa-angle-left" style="font-size: 1.3rem; padding-left: 1rem;"></i>
            <i id="arrow-right" class="fa-solid fa-angle-right" style="font-size: 1.3rem; padding: 0;"></i>
          </div>
        </div>
        <div class="cards1">
          <div>
            <ul>
              <li>
                <a href="produto2.html"><ul class="foto-like" style="background-image: url(IMG/camiseta\ azul.jpg);">
                  <div class="heart"><i class="bi bi-heart"></i></div>
                </ul></a>
                <a href="produto2.html"><p class="titulo-produto">Camiseta Dry Fit Blue</p></a>
                <div class="precos">
                  <p class="preco-produto-credito">R$99,90</p>
                  <p class="preco-produto-pix">R$95,90</p>
                  <p class="preco-pix">(NO PIX)</p>
                </div>
                <p class="pagamento-produto">Ou R$99,90 no cartão de crédito <br> em até 2x sem juros</p>
              </li>
            </ul>
          </div> 
          <div>
            <ul>
              <li>
                <a href="produto3.html"><ul class="foto-like" style="background-image: url(IMG/camiseta\ vermelha.jpg);">
                  <div class="heart"><i class="bi bi-heart"></i></div>
                </ul></a>
                <a href="produto3.html"><p class="titulo-produto">Camiseta Dry Fit Red</p></a>
                <div class="precos">
                  <p class="preco-produto-credito">R$99,90</p>
                  <p class="preco-produto-pix">R$95,90</p>
                  <p class="preco-pix">(NO PIX)</p>
                </div>
                <p class="pagamento-produto">Ou R$99,90 no cartão de crédito <br> em até 2x sem juros</p>
              </li>
            </ul>
          </div>  
          <div>
            <ul>
              <li>
                <a href="produto11.html"><ul class="foto-like" style="background-image: url(IMG/regata\ preta.jpg); background-size: cover;">
                  <div class="heart"><i class="bi bi-heart"></i></div>
                </ul></a>
                <a href="produto11.html"><p class="titulo-produto">Regata Preta</p></a>
                <div class="precos">
                  <p class="preco-produto-credito">R$99,90</p>
                  <p class="preco-produto-pix">R$95,90</p>
                  <p class="preco-pix">(NO PIX)</p>
                </div>
                <p class="pagamento-produto">Ou R$99,90 no cartão de crédito <br> em até 2x sem juros</p>
              </li>
            </ul>
          </div>  
          <div>
            <ul>
              <li>
                <a href="produto4.html"><ul class="foto-like" style="background-image: url(IMG/camiseta\ offwhite.jpg);">
                  <div class="heart"><i class="bi bi-heart"></i></div>
                </ul></a>
                <a href="produto4.html"><p class="titulo-produto">Camiseta Dry Fit Offwhite</p></a>
                <div class="precos">
                  <p class="preco-produto-credito">R$99,90</p>
                  <p class="preco-produto-pix">R$95,90</p>
                  <p class="preco-pix">(NO PIX)</p>
                </div>
                <p class="pagamento-produto">Ou R$99,90 no cartão de crédito <br> em até 2x sem juros</p>
              </li>
            </ul>
          </div>  
          <div>
            <ul>
              <li>
                <a href="produto5.html"><ul class="foto-like" style="background-image: url(IMG/camiseta\ bege.jpg);">
                  <div class="heart"><i class="bi bi-heart"></i></div>
                </ul></a>
                <a href="produto5.html"><p class="titulo-produto">Camiseta Dry Fit Beige</p></a>
                <div class="precos">
                  <p class="preco-produto-credito">R$99,90</p>
                  <p class="preco-produto-pix">R$95,90</p>
                  <p class="preco-pix">(NO PIX)</p>
                </div>
                <p class="pagamento-produto">Ou R$99,90 no cartão de crédito <br> em até 2x sem juros</p>
              </li>
            </ul>
          </div>
        </div>
      </section>
      <a class="top" href="">VOLTAR AO TOPO</button></a>
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
                        <li><a href="#">Camisetas</a></li>
                        <li><a href="#">Bermudas</a></li>
                        <li><a href="#">Calças</a></li>
                        <li><a href="#">Acessórios</a></li>
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
      <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous" defer></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
      <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
      <script src="https://kit.fontawesome.com/43b36f20b7.js" crossorigin="anonymous"></script>
      <script src="JAVASCRIPT/slick.min.js"></script>
      <script src="JAVASCRIPT/slick.js"></script>
      <script src="JAVASCRIPT/funcoes.js"></script>
      <script src="JAVASCRIPT/miniCart.js"></script>
      <script src="JAVASCRIPT/addcarrinho.js"></script>
    </body>
    </html>
