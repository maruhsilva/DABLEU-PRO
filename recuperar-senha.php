<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Certifique-se de que o PHPMailer está incluído corretamente
include "classes/config.php";

$modalMessage = '';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Verificar se o email existe no banco
    $sql_code = "SELECT * FROM usuarios WHERE email = '$email'";
    $sql_query = $mysqli->query($sql_code);

    if ($sql_query->num_rows == 1) {
        // Gerar um token para a recuperação
        $token = bin2hex(random_bytes(16));
        $expire = date("Y-m-d H:i:s", strtotime('+1 hour')); // O link expira em 1 hora

        // Salvar o token no banco
        $sql_code = "UPDATE usuarios SET token_recuperacao = '$token', token_expira = '$expire' WHERE email = '$email'";
        $mysqli->query($sql_code);

        // Enviar o e-mail com PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuração do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Defina o host do seu servidor SMTP (p.ex., Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'marua.desenvolvedor@gmail.com'; // Seu e-mail
            $mail->Password = 'yuvi xged gwlv pupa'; // Sua senha de aplicativo (para Gmail, use a senha de aplicativo, não a senha da conta)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Desativar verificação SSL (não recomendado para produção)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Destinatário e assunto
            $mail->setFrom('marua.desenvolvedor@gmail.com', 'Dableu Pro');
            $mail->addAddress($email);
            $mail->Subject = 'Recuperação de Senha';

            // Corpo do e-mail
            $link = "http://localhost/dableu-pro/recuperar-senha-form.php?token=$token"; // URL da página de redefinição
            $message = "Clique no link para redefinir sua senha: $link";
            $mail->Body = $message;

            // Enviar o e-mail
            $mail->send();
            $modalMessage = 'Um link para redefinir sua senha foi enviado para o seu e-mail.';
        } catch (Exception $e) {
            $modalMessage = "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
        }
    } else {
        $modalMessage = 'Este e-mail não está cadastrado!';
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
                  <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    MASCULINO
                  </a>
                  
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    FEMININO
                  </a>
                  
                </li> 
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    KITS
                  </a>
                  
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            }
        </style>

        <section class="restore">
            <div class="restore-form">
            <h2>Recuperação de Senha</h2><br>
                <form method="POST">
                    <label for="email">Digite seu e-mail:</label><br>
                    <input type="email" name="email" required><br><br>
                    <input type="submit" id="button-send" value="Enviar Link de Recuperação">
                </form>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"> <!-- Adicionando a classe modal-dialog-centered para centralizar -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Notificação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (isset($modalMessage)) { echo $modalMessage; } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adicionando o script do Bootstrap após o conteúdo -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Script para mostrar o modal -->
        <script>
            // Verifica se há uma mensagem no PHP e exibe o modal
            <?php if (isset($modalMessage) && !empty($modalMessage)) { ?>
                var myModal = new bootstrap.Modal(document.getElementById('emailModal'));
                myModal.show();
            <?php } ?>
        </script>

      <footer>
      
        <div class="desenvolvedor">
          <p>© 2024 DableuPro LTDA | CNPJ: XX.XXX.XXX/XXXX-XX | Rua Cliente, XXX - Jacareí - São Paulo | CEP: XX.XXX-XXX - Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.png" alt="logo do desenvolvedor"></a>
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
