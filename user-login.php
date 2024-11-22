<?php
include "classes/config.php";

$error_email = false;
$error_senha = false;

if (isset($_POST["email"]) || isset($_POST["senha"])) {
    if (strlen($_POST["email"]) == 0) {
        $error_email = true;
    } else if (strlen($_POST["senha"]) == 0) {
        $error_senha = true;
    } else {
        // Proteção contra SQL Injection
        $email = $mysqli->real_escape_string($_POST["email"]);
        $senha = $mysqli->real_escape_string($_POST["senha"]); // MD5 pode ser ajustado se necessário

        // Verifica se o e-mail existe
        $sql_email = "SELECT * FROM usuarios WHERE email = '$email'";
        $query_email = $mysqli->query($sql_email);

        if ($query_email->num_rows == 0) {
            $error_email = true;
        } else {
            // Se o e-mail existe, verifica a senha
            $usuario = $query_email->fetch_assoc();
            if ($usuario['senha'] != $senha) {
                $error_senha = true;
            } else {
                // Login bem-sucedido
                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];

                header('Location: user-logado.php');
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
    <link rel="shortcut icon" type="imagex/png" href="IMG/BARRAS-PRETAS-4cm-6cm-_2_.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="CSS/slick-theme.css">
    <link rel="stylesheet" href="CSS/slick.css">
    <link rel="stylesheet" href="CSS/user-login.css">
</head>
<body>
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999;  display: flex; align-items: center;">
        <div class="container-fluid justify-content-around" style="gap: 5rem;">
          <a class="navbar-brand" href="index.html"><img src="IMG/NOME 8cm - BRANCO E PRETO (2).png" alt="logo da empresa" style="width: 10rem; padding-bottom: .2rem;"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
            <div class="collapse navbar-collapse flex-grow-0" id="navbarNavDropdown" style="font-size: 1.05rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
                <ul class="navbar-nav" style="gap: 2rem; display: flex; align-items: center;">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.html"><i class="fa-solid fa-house"></i></a>
                    <!-- <ul class="icons">
                      <div class="btn-group dropstart">
                        <button type="button" class="btn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" style="border: none; padding-right: 1rem; font-size: 1.5rem; align-items: center;">
                          <i class="bi bi-person"></i>
                        </button>
                        <form class="dropdown-menu p-4">
                          <div class="mb-3" style="min-width: 15rem;">
                            <label for="exampleDropdownFormEmail2" class="form-label">Email</label>
                            <input type="email" class="form-control" id="exampleDropdownFormEmail2" placeholder="email@exemplo.com">
                          </div>
                          <div class="mb-3">
                            <label for="exampleDropdownFormPassword2" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="exampleDropdownFormPassword2" placeholder="Senha">
                          </div>
                          <div class="mb-3">
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="dropdownCheck2">
                              <label class="form-check-label" for="dropdownCheck2">
                                Lembre-me
                              </label>
                            </div>
                          </div>
                          <button type="submit" class="btn btn-dark">Entrar</button>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" style="letter-spacing: 0; font-size: .9rem; padding-left: 0;" href="#">Novo por aqui? Cadastre-se!</a>
                          <a class="dropdown-item" style="letter-spacing: 0; font-size: .9rem; padding-left: 0;" href="#">Esqueceu sua senha?</a>
                        </form>
                      </div>
                      <li>
                        <a href=""><i class="bi bi-search"></i></a>
                        <a href=""><i class="bi bi-heart"></i></a>
                        <a href=""><i class="bi bi-bag"></i></a>
                      </li>  
                    </ul>   -->
                </ul>
            </div>
        </div>
      </nav>
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
                        <li><a href="#">Camisetas</a></li>
                        <li><a href="#">Bermudas</a></li>
                        <li><a href="#">Calças</a></li>
                        <li><a href="#">Acessórios</a></li>
                    </ul>
                </div>
                
                
                <div class="footer-col">
                    <h4>Se inscreva!</h4>
                    <div class="form-sub">
                        <form>
                            <input type="email" placeholder="Digite o seu e-mail">
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
  
    </body>
    </html>
