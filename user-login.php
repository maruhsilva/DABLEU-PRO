<?php
include __DIR__ . "/config.php";

session_start(); // Inicia a sessão

$error_email = false;
$error_senha = false;

// Função para validar URLs internas
function is_internal_url($url) {
    $host = parse_url($url, PHP_URL_HOST);
    return $host === null || $host === $_SERVER['HTTP_HOST'];
}

// Salva a URL de origem apenas se ela for válida e não for a página de login
if (!isset($_SESSION['redirect_to']) && isset($_SERVER['REQUEST_URI'])) {
    $current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($current_page !== 'user-login.php' && is_internal_url($_SERVER['REQUEST_URI'])) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error_email = true;
    } elseif (strlen($_POST["senha"]) === 0) {
        $error_senha = true;
    } else {
        // Proteção contra SQL Injection
        $email = $mysqli->real_escape_string($_POST["email"]);
        $senha = $_POST["senha"];

        // Verifica se o e-mail existe
        $sql_email = "SELECT * FROM usuarios WHERE email = '$email'";
        $query_email = $mysqli->query($sql_email);

        if ($query_email->num_rows === 0) {
            $error_email = true;
        } else {
            $usuario = $query_email->fetch_assoc();

            // Verifica a senha
           if ($senha !== $usuario['senha']) {
            $error_senha = true;
        } else {
            // Login bem-sucedido
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];

                // Redireciona para a página de origem ou página padrão
                $redirect_to = $_SESSION['redirect_to'] ?? 'user-logado.php';
                unset($_SESSION['redirect_to']);

                // Valida o redirecionamento para garantir que a URL seja interna
                if (!is_internal_url($redirect_to)) {
                    $redirect_to = 'user-logado.php';
                }

                header("Location: $redirect_to");
                exit;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dableu Pro - Moda Fitness Masculina e Feminina</title>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MGJKWKWH');</script>
    <!-- End Google Tag Manager -->
    <link rel="shortcut icon" type="imagex/png" href="IMG/BARRAS-PRETAS-4cm-6cm-_2_.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="CSS/slick-theme.css">
    <link rel="stylesheet" href="CSS/slick.css">
    <link rel="stylesheet" href="CSS/user-login.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGJKWKWH"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999;  display: flex; align-items: center;">
        <div class="container-fluid justify-content-around" style="gap: 5rem;">
        <a class="navbar-brand" href="index.html"><img src="IMG/NOME 10cm COM R.webp" alt="logo da empresa" style="width: 7rem; padding-bottom: .2rem;"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
            <div class="collapse navbar-collapse flex-grow-0" id="navbarNavDropdown" style="font-size: 1.05rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                <ul class="navbar-nav" style="gap: 2rem; display: flex; align-items: center;">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.html"><i class="fa-solid fa-house"></i></a>
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
      <section class="login">
        <h1>ÁREA DE LOGIN | <a href="user-cadastro.php" style="text-decoration: none; color: black;">ÁREA DE CADASTRO</a></h1>
        <form action="" method="post">
            <div class="row mb-3" style="padding-bottom: 1rem;">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" id="inputEmail3" required
                        style="<?php echo $error_email ? 'border-color: red;' : ''; ?>">
                    <div id="email-popover" class="popover" 
                         style="<?php echo $error_email ? 'display: block;' : 'display: none;'; ?>">
                        E-mail inválido ou não encontrado!
                    </div>
                </div>
            </div>
            <div class="row mb-3" style="padding-bottom: 1rem;" id="input">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Senha</label>
                <div class="col-sm-10" id="senha-eye">
                    <input type="password" name="senha" class="form-control" id="inputPassword3" required
                        style="<?php echo $error_senha ? 'border-color: red;' : ''; ?>">
                    <i class="fa-solid fa-eye"></i>
                    
                </div>
                <div id="senha-popover" class="popover" 
                   style="<?php echo $error_senha ? 'display: block;' : 'display: none;'; ?>; margin-left: 7.2rem;">
                  Senha incorreta!
                </div>
            </div>
            <div class="btns" style="display: flex;">
                <input type="submit" name="submit" value="Entrar" class="btn btn-dark">
                <a href="recuperar-senha.php" style="color: #007bff; text-decoration: none;">Esqueci a minha senha</a>
            </div>
            
        </form>
      </section>

      <!-- <a class="top" href="">VOLTAR AO TOPO</button></a> -->

      <script>
          document.addEventListener("DOMContentLoaded", () => {
          const emailInput = document.getElementById("inputEmail3");
          const emailPopover = document.getElementById("email-popover");
          const senhaInput = document.getElementById("inputPassword3");
          const senhaPopover = document.getElementById("senha-popover");

          // Remove estilo de erro e esconde popover quando o campo é focado
          emailInput.addEventListener("focus", () => {
              emailInput.style.borderColor = "";
              emailPopover.style.display = "none";
          });
        
          senhaInput.addEventListener("focus", () => {
              senhaInput.style.borderColor = "";
              senhaPopover.style.display = "none";
          });
        });
      </script>


      <footer>
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
  
    </body>
    </html>
