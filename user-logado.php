<?php

require_once __DIR__ . '/protect.php';
require_once __DIR__ . '/usuarios.php';

// Caminho do log de erros
$logFile = __DIR__ . '/logs/errors.log';

// Função para registrar erros
function logError($message) {
    global $logFile;
    error_log("[" . date("Y-m-d H:i:s") . "] $message\n", 3, $logFile);
}

try {
    // Inicializando a conexão com o banco de dados
    $usuario = new Usuario();
    if (!$usuario->conectar('login_dableu', 'login_dableu.mysql.dbaas.com.br', 'login_dableu', 'Marua3902@')) {
        logError("Erro na conexão: " . $usuario->msgErro);
        echo "Erro ao conectar com o banco.";
        exit;
    }

    // Verifica se o usuário está autenticado
    if (!isset($_SESSION['id_usuario'])) {
        logError("Usuário não autenticado. Acesso negado.");
        echo "Usuário não autenticado.";
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];

    // Consultar os dados do usuário
    $pdo = $usuario->getPdo(); // Obtém a conexão PDO da classe
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
    $sql->bindValue(":id", $id_usuario, PDO::PARAM_INT);
    $sql->execute();

    $usuario_data = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$usuario_data) {
        logError("Nenhum dado encontrado para o usuário com ID: $id_usuario");
        echo "Nenhum dado encontrado para este usuário.";
        exit;
    }

} catch (PDOException $e) {
    logError("Erro ao buscar os dados do usuário: " . $e->getMessage());
    echo "Erro ao buscar os dados do usuário.";
    exit;
} catch (Exception $e) {
    logError("Erro geral: " . $e->getMessage());
    echo "Erro inesperado.";
    exit;
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="CSS/slick-theme.css">
    <link rel="stylesheet" href="CSS/slick.css">
    <link rel="stylesheet" href="CSS/user-logado.css">
</head>
<body>
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999;  display: flex; align-items: center;">
      <div class="container-fluid justify-content-center" style="gap: 5rem;">
      <a class="navbar-brand" href="index.html"><img src="IMG/NOME 10cm COM R.png" alt="logo da empresa" style="width: 7rem; padding-bottom: .2rem;"></a>
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
      <section class="infos1" style="margin-top: 3rem; margin-bottom: 3rem;">
        <div class="dados-user">
          <p>
          Bem-vindo(a) a ÁREA DO CLIENTE, <strong><?php echo $_SESSION['nome']; ?></strong>.
          </p>
          </div>
      </section>
      <!-- Seção de Dados Pessoais -->
      <section class="edit" style="gap: 1rem">
        <button><a href="logout.php">Sair</a></button>
        <button><a href="meus-pedidos.php">Meus Pedidos</a></button>
        <button><a href="editar-dados.php">Editar Dados</a></button>
      </section>
    <section style="flex-direction: column; align-items: center; justify-content: baseline;">
        <h2>Dados Pessoais</h2>
        <table border="1">
            <tr>
                <th>Nome:</th>
                <td><?php echo $usuario_data['nome']; ?></td>
            </tr>
            <tr>
                <th>CPF:</th>
                <td><?php echo $usuario_data['cpf']; ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?php echo $usuario_data['email']; ?></td>
            </tr>
            <tr>
                <th>Telefone:</th>
                <td><?php echo $usuario_data['telefone']; ?></td>
            </tr>
        </table>
    </section>
    
    <!-- Seção de Endereços -->
    <section>
        <h2>Endereços</h2>
        <table border="1">
            <tr>
                <th>CEP:</th>
                <td><?php echo $usuario_data['cep']; ?></td>
            </tr>
            <tr>
                <th>Endereço:</th>
                <td><?php echo $usuario_data['endereco']; ?></td>
            </tr>
            <tr>
                <th>Número:</th>
                <td><?php echo $usuario_data['numero']; ?></td>
            </tr>
            <tr>
                <th>Complemento:</th>
                <td><?php echo $usuario_data['complemento']; ?></td>
            </tr>
            <tr>
                <th>Bairro:</th>
                <td><?php echo $usuario_data['bairro']; ?></td>
            </tr>
            <tr>
                <th>Cidade:</th>
                <td><?php echo $usuario_data['cidade']; ?></td>
            </tr>
            <tr>
                <th>Estado:</th>
                <td><?php echo $usuario_data['estado']; ?></td>
            </tr>
          </table>
        </section>
        
        <!-- Link para editar os dados -->
        
      <a class="top" href="">VOLTAR AO TOPO</button></a>

      <footer>
        
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
      <script src="JAVASCRIPT/miniCart.js"></script>
      <script src="JAVASCRIPT/addcarrinho.js"></script>
    </body>
    </html>
