<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

require_once 'classes/usuarios.php';

// Cria a instância da classe Usuario e conecta ao banco de dados
$usuario = new Usuario();
if (!$usuario->conectar('login_dableupro', 'localhost', 'root', '')) {
    die("Erro na conexão com o banco de dados: " . $usuario->msgErro);
}

// Obtém o objeto PDO da classe Usuario
$pdo = $usuario->getPdo();

// Obtém o ID do usuário logado
$id_usuario = $_SESSION['id_usuario'];

// Busca as informações do usuário logado
$sql = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
$sql->bindValue(":id", $id_usuario);
$sql->execute();
$usuario_data = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario_data) {
    echo "Erro: Usuário não encontrado.";
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $endereco = $_POST['endereco'] ?? '';  // Alterado para 'endereço'
    $numero = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';

    try {
        // Atualiza os dados do usuário no banco de dados
        $sql = $pdo->prepare("UPDATE usuarios SET 
            nome = :n, 
            cpf = :c, 
            email = :e, 
            telefone = :t,
            cep = :cep, 
            endereco = :end,
            numero = :num, 
            complemento = :comp, 
            bairro = :bairro,
            cidade = :cidade, 
            estado = :estado 
            WHERE id_usuario = :id");

        $sql->bindValue(":n", $nome);
        $sql->bindValue(":c", $cpf);
        $sql->bindValue(":e", $email);
        $sql->bindValue(":t", $telefone);
        $sql->bindValue(":cep", $cep);
        $sql->bindValue(":end", $endereco);  // Alterado para 'endereço'
        $sql->bindValue(":num", $numero);
        $sql->bindValue(":comp", $complemento);
        $sql->bindValue(":bairro", $bairro);
        $sql->bindValue(":cidade", $cidade);
        $sql->bindValue(":estado", $estado);
        $sql->bindValue(":id", $id_usuario);

        $sql->execute();

        // Exibe mensagem de sucesso e redireciona
        echo "Dados atualizados com sucesso!";
        header('Location: user-logado.php');
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar os dados: " . $e->getMessage();
    }
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
    <link rel="stylesheet" href="CSS/editar-dados.css">
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

      <section class="infos1">
        <div class="dados-user">
          <p>
          Bem-vindo(a) a ÁREA DO CLIENTE, <strong><?php echo $_SESSION['nome']; ?></strong>.
          </p>
          <p>
            <a href="classes/logout.php">Sair</a>
          </p>
        </div>
      </section>
      <section>
      <h1>Editar Dados</h1>

            <form method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $usuario_data['nome']; ?>" required><br><br>

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" value="<?php echo $usuario_data['cpf']; ?>" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $usuario_data['email']; ?>" required><br><br>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo $usuario_data['telefone']; ?>" required><br><br>

                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" value="<?php echo $usuario_data['cep']; ?>" required><br><br>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo $usuario_data['endereco']; ?>" required><br><br>

                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?php echo $usuario_data['numero']; ?>" required><br><br>

                <label for="complemento">Complemento:</label>
                <input type="text" id="complemento" name="complemento" value="<?php echo $usuario_data['complemento']; ?>"><br><br>

                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" value="<?php echo $usuario_data['bairro']; ?>" required><br><br>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo $usuario_data['cidade']; ?>" required><br><br>

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?php echo $usuario_data['estado']; ?>" required><br><br>

                <button class="confirmar" type="submit">Atualizar Dados</button>
                <button class="cancelar"><a href="user-logado.php">Cancelar</a></button>
            </form>
          </section>
      <footer>
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
      <!-- <script src="JAVASCRIPT/addcarrinho.js"></script> -->
</body>
</html>
