<?php
session_start();

// Configuração do banco de dados
$host = 'login_dableu.mysql.dbaas.com.br';
$db = 'login_dableu';
$user = 'login_dableu';
$password = 'Marua3902@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}

// Recuperar o ID do usuário logado
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
  header("Location: user-login.php"); // Redireciona para a página de login, se necessário
  exit;
}
// Buscar os pedidos do usuário no banco de dados
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = :id_usuario ORDER BY data_pedido DESC");
$stmt->execute(['id_usuario' => $id_usuario]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar os detalhes do pedido, caso seja solicitado via GET
$id_pedido = isset($_GET['id']) ? $_GET['id'] : null;
if ($id_pedido) {
    // Buscar os detalhes do pedido
    $stmt_pedido = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido");
    $stmt_pedido->execute(['id_pedido' => $id_pedido]);
    $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

    // Buscar os itens do pedido
    $stmt_items = $pdo->prepare("SELECT * FROM itens_pedido_definitivo WHERE id_pedido = :id_pedido");
    $stmt_items->execute(['id_pedido' => $id_pedido]);
    $itens = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dableu Pro - Meus Pedidos</title>
    <link rel="shortcut icon" type="imagex/png" href="IMG/BARRAS-PRETAS-4cm-6cm-_2_.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/index.css">
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

    <script>
        // Função para exibir/ocultar os detalhes do pedido
        function toggleDetalhes(id) {
            var detalhes = document.getElementById("detalhes-" + id);
            if (detalhes.style.display === "none" || detalhes.style.display === "") {
                detalhes.style.display = "table-row";
            } else {
                detalhes.style.display = "none";
            }
        }
    </script>
    
        <style>

        @media (max-width: 800px) {

          .table {
            max-width: 100vw;
            font-size: .7rem;
            text-align: center;
          }

          .btn.btn-warning {
            font-size: .7rem;
            display: flex;
            align-items: baseline;
            }

          tbody tr {
            text-align: center;
          }
        }

        .icons {
          display: none;
        }
          
        </style>

    <div class="container mt-5">
        <h1 class="text-center">Meus Pedidos</h1>

        <?php if (count($pedidos) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#ID Pedido</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Detalhes</th>
                        <th>Ação</th> <!-- Adicionando a coluna de ação -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= isset($pedido['id_pedido']) ? $pedido['id_pedido'] : 'N/A' ?></td>
                            <td><?= isset($pedido['data_pedido']) ? date('d/m/Y H:i:s', strtotime($pedido['data_pedido'])) : 'N/A' ?></td>
                            <td><?= isset($pedido['status']) ? ucfirst($pedido['status']) : 'N/A' ?></td>
                            <td>R$ <?= isset($pedido['total']) ? number_format($pedido['total'], 2, ',', '.') : '0,00' ?></td>
                            <td><button onclick="toggleDetalhes(<?= $pedido['id_pedido'] ?>)" style="background-color: black; border: none; color: white; padding: 2px 5px; font-family: Georgia; font-weight: bold;">Ver mais</button></td>

                            <td>
                                <?php if (isset($pedido['status']) && ($pedido['status'] === 'rejeitado' || $pedido['status'] === 'pendente')): ?>
                                    <a href="refazer-pedido.php?id_pedido=<?= $pedido['id_pedido'] ?>" class="btn btn-warning">Refazer Pedido</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        
                        <!-- Detalhes do pedido -->
                        <tr id="detalhes-<?= $pedido['id_pedido'] ?>" style="display:none;">
                            <td colspan="6">
                                <h3>Detalhes do Pedido</h3>
                                <table class="table">
                                    <thead>
                                        <tr>

                                            <th>Imagem</th>
                                            <th>Produto</th>
                                            <th>Quantidade</th>
                                            <th>Preço Unitário</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Carregar os itens do pedido
                                        $stmt_items = $pdo->prepare("SELECT * FROM itens_pedido WHERE id_pedido = :id_pedido");
                                        $stmt_items->execute(['id_pedido' => $pedido['id_pedido']]);
                                        $itens = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($itens as $item):
                                        ?>
                                            <tr>
                                                <td><?= $item['imagem_produto'] ?></td>
                                                <td><?= $item['nome_produto'] ?></td>
                                                <td><?= $item['quantidade'] ?></td>
                                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                                <td>R$ <?= number_format($item['quantidade'] * $item['preco'], 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você ainda não fez nenhum pedido.</p>
        <?php endif; ?>
    </div>


    <footer class="text-center mt-5">
    <div class="desenvolvedor">
          <p>© 2024 DableuPro LTDA | Jacareí - São Paulo | Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.webp" alt="logo do desenvolvedor"></a>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
    <script src="https://kit.fontawesome.com/43b36f20b7.js" crossorigin="anonymous"></script>
    <script src="JAVASCRIPT/slick.min.js"></script>
    <script src="JAVASCRIPT/slick.js"></script>
    <script src="JAVASCRIPT/funcoes.js"></script>
    <script src="JAVASCRIPT/addcarrinho.js"></script>
</body>
</html>
