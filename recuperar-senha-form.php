<?php

require 'vendor/autoload.php'; // Certifique-se de que o PHPMailer está incluído corretamente
include "config.php";

$modalMessage = ''; // Mensagem do modal
$token = $_GET['token'] ?? ''; // Recuperar o token da URL

// Verificar se o token é válido
if ($token) {
    // Buscar o token no banco de dados
    $sql_code = "SELECT * FROM usuarios WHERE token_recuperacao = '$token' AND token_expira > NOW()";
    $sql_query = $mysqli->query($sql_code);

    if ($sql_query->num_rows == 1) {
        // Formulário de recuperação de senha
        if (isset($_POST['nova_senha']) && isset($_POST['confirmar_senha'])) {
            $nova_senha = $_POST['nova_senha'];
            $confirmar_senha = $_POST['confirmar_senha'];

            // Verificar se as senhas coincidem
            if ($nova_senha === $confirmar_senha) {
                // Atualizar a senha no banco de dados sem criptografar
                $sql_code = "UPDATE usuarios SET senha = '$nova_senha', token_recuperacao = NULL, token_expira = NULL WHERE token_recuperacao = '$token'";

                if ($mysqli->query($sql_code)) {
                    $modalMessage = 'Senha alterada com sucesso!';
                } else {
                    $modalMessage = 'Erro ao alterar a senha. Tente novamente.';
                }
            } else {
                $modalMessage = 'As senhas não coincidem.';
            }
        }
    } else {
        $modalMessage = 'O link de recuperação expirou ou é inválido.';
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
      
        <style>
            .restore {
                min-height: 60vh;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }

            h2 {
                font-family: Georgia, 'Times New Roman', Times, serif;
                text-transform: uppercase;
                font-size: 1.5rem;
            }

            .restore-form {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                border: 1px solid white;
                padding: 5rem;
                border-radius: 1rem;
                box-shadow: 0px 0px 5px 5px rgba(0%, 0%, 0%, .4);
            }

            #button-send {
                background-color: black;
                color: #fff;
                font-size: .9rem;
                padding: .4rem;
                border: none;
                border-radius: .5rem;
            }
        </style>

        <!-- Modal de Sucesso ou Erro -->
    <div class="modal fade" id="senhaAlteradaModal" tabindex="-1" aria-labelledby="senhaAlteradaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="senhaAlteradaModalLabel">Alteração de Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($modalMessage)) { echo $modalMessage; } ?>
                </div>
                <div class="modal-footer">
                    <a href="user-login.php" class="btn btn-primary">Ir para Login</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
        <div class="restore">
            <div class="restore-form">
            <h2>Redefinir Senha</h2><br>
            <form method="POST">
                <label for="nova_senha">Nova Senha:</label><br>
                <input type="password" name="nova_senha" required><br><br>
                <label for="confirmar_senha">Confirmar Senha:</label><br>
                <input type="password" name="confirmar_senha" required><br><br>
                <input type="submit" id="button-send" value="Redefinir Senha">
            </form>
            </div>
        </div>

    <!-- Importação do Bootstrap JS para funcionalidades do modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (isset($modalMessage) && !empty($modalMessage)): ?>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('senhaAlteradaModal'));
            myModal.show(); // Exibe o modal automaticamente após o envio do formulário
        </script>
    <?php endif; ?>
      <footer>
      
      <div class="desenvolvedor">
          <p>© 2024 DableuPro LTDA | Jacareí - São Paulo | Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.webp" alt="logo do desenvolvedor"></a>
        </div>
    </footer>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
      <script src="https://kit.fontawesome.com/43b36f20b7.js" crossorigin="anonymous"></script>
      <script src="JAVASCRIPT/slick.min.js"></script>
      <script src="JAVASCRIPT/slick.js"></script>
      <script src="JAVASCRIPT/funcoes.js"></script>
      <script src="JAVASCRIPT/addcarrinho.js"></script>
    </body>
</html>
