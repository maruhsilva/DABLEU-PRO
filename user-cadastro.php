<?php

  require_once __DIR__ . '/usuarios.php';
  $u = new Usuario;

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
    <link rel="stylesheet" href="CSS/user-cadastro.css">
</head>
<body>
    <header>
        <p>FRETE GRÁTIS EM SP PARA COMPRAS À PARTIR DE R$250,00</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-white nav-justified" style="position: sticky; top: 0; background-color: white; border-bottom: .5px solid hsl(0, 0%, 0%, .2); padding: .5rem; z-index: 9999;  display: flex; align-items: center;">
        <div class="container-fluid justify-content-around" style="gap: 5rem;">
        <a class="navbar-brand" href="index.html"><img src="IMG/NOME 10cm COM R.png" alt="logo da empresa" style="width: 7rem; padding-bottom: .2rem;"></a>
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

      <section class="forms">
          <h1>ÁREA DE CADASTRO | <a href="user-login.php" style="text-decoration: none; color: black;">ÁREA DE LOGIN</a></h1>
          <div class="formulario-cadastro">
          <h2>Dados Pessoais</h2>
          <form class="row g-3 align-items-center" method="POST">
            <div class="col-md-6" >
                <label for="inputName4" class="form-label">Nome Completo</label>
                <input type="text" name="nome" class="form-control" id="inputName4" maxlength="30" required>
            </div>
            <div class="col-md-6" >
                <label for="inputCPF4" class="form-label">CPF</label>
                <input type="text" name="cpf" class="form-control" id="inputCPF4" maxlength="16" required>
            </div>
            <div class="col-md-6" >
              <label for="inputEmail4" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="inputEmail4" maxlength="30" required>
            </div>
            <div class="col-md-6" >
                <label for="inputTel4" class="form-label">Telefone</label>
                <input type="tel" name="telefone" class="form-control" id="inputTel4" maxlength="30" required>
              </div>
            <div class="col-md-6" id="input">
              <label for="inputPassword6" class="col-form-label" style="justify-content: space-between; display: flex; align-items: center;">Senha</i></label>
              <input type="text" id="inputPassword6" name="senha" class="form-control" placeholder="8 à 12 caracteres" minlength="8" maxlength="12" aria-describedby="passwordHelpInline">
            </div>
            <div class="col-md-6" id="input">
              <label for="inputPassword6" class="col-form-label" style="justify-content: space-between; display: flex; align-items: center;">Confirmar Senha</i></label>
              <input type="text" id="inputPassword6" name="confsenha" class="form-control" placeholder="8 à 12 caracteres" minlength="8" maxlength="12" aria-describedby="passwordHelpInline">
            </div>
          <!-- </form> -->
          <h2>Endereço</h2>
          <!-- <form class="row g-3 align-items-center" method="post"> -->
              <div class="col-md-6">
                <label for="inputZip" class="form-label">CEP</label>
                <input type="text" name="cep" class="form-control" id="inputZip" maxlength="30">
              </div>
            <div class="col-md-6" >
              <label for="inputAddress" class="form-label">Endereço</label>
              <input type="text" name="endereço" class="form-control" id="inputAddress" placeholder="Rua/Av." required maxlength="30">
            </div>
            <div class="col-md-6" >
              <label for="inputNumber" class="form-label">Número</label>
              <input type="text" name="numero" class="form-control" id="inputNumber" required maxlength="30">
            </div>
            <div class="col-md-6" >
              <label for="inputAddress" class="form-label">Complemento</label>
              <input type="text" name="complemento" class="form-control" id="inputAddress" placeholder="Bloco/Apto." maxlength="30">
            </div>
            <div class="col-md-6" >
              <label for="inputAddress2" class="form-label">Bairro</label>
              <input type="text" name="bairro" class="form-control" id="inputAddress2" required maxlength="30">
            </div>
            <div class="col-md-6" >
              <label for="inputCity" class="form-label">Cidade</label>
              <input type="text" name="cidade" class="form-control" id="inputCity" maxlength="30">
            </div>
            <div class="col-md-6" >
              <label for="inputState" class="form-label">Estado</label>
              <select id="inputState" name="estado" class="form-select" maxlength="30">
                <option selected>Selecione...</option>
                <option value="AC">Acre</option>
			          <option value="AL">Alagoas</option>
			          <option value="AP">Amapá</option>
			          <option value="AM">Amazonas</option>
			          <option value="BA">Bahia</option>
			          <option value="CE">Ceará</option>
		    	      <option value="DF">Distrito Federal</option>
			          <option value="ES">Espírito Santo</option>
			          <option value="GO">Goiás</option>
			          <option value="MA">Maranhão</option>
			          <option value="MT">Mato Grosso</option>
			          <option value="MS">Mato Grosso do Sul</option>
			          <option value="MG">Minas Gerais</option>
			          <option value="PA">Pará</option>
			          <option value="PB">Paraíba</option>
			          <option value="PR">Paraná</option>
			          <option value="PE">Pernambuco</option>
			          <option value="PI">Piauí</option>
			          <option value="RJ">Rio de Janeiro</option>
			          <option value="RN">Rio Grande do Norte</option>
			          <option value="RS">Rio Grande do Sul</option>
			          <option value="RO">Rondônia</option>
			          <option value="RR">Roraima</option>
			          <option value="SC">Santa Catarina</option>
			          <option value="SP">São Paulo</option>
			          <option value="SE">Sergipe</option>
			          <option value="TO">Tocantins</option>
              </select>
            </div>
            <div class="col-12" style="padding-top: 2rem;" >
              <button type="submit" name="submit" class="btn btn-dark">Cadastrar</button>
              <a href="user-login.php">Já possui conta? Clique aqui!</a>
            </div>
          </form>
      </section>

      <?php
       if (isset($_POST['nome']))
      {
        $nome = addslashes($_POST['nome']);
        $cpf = addslashes($_POST['cpf']);
        $email = addslashes($_POST['email']);
        $telefone = addslashes($_POST['telefone']);
        $senha = addslashes($_POST['senha']); 
        $confirmarSenha = addslashes($_POST['confsenha']);

        $cep = addslashes($_POST['cep']);
        $endereço = addslashes($_POST['endereço']);
        $numero = addslashes($_POST['numero']);
        $complemento = addslashes($_POST['complemento']);
        $bairro = addslashes($_POST['bairro']);
        $cidade = addslashes($_POST['cidade']);
        $estado = addslashes($_POST['estado']);

        if(!empty($nome) && !empty($cpf) && !empty($email) && !empty($telefone) && !empty($senha) 
        && !empty($confirmarSenha) && !empty($cep) && !empty($endereço) && !empty($numero) &&
        !empty($bairro) && !empty($cidade) && !empty($estado))
        {
          $u->conectar("login_dableu", "login_dableu.mysql.dbaas.com.br", "login_dableu", "Marua3902@");
          if (empty($_POST[""]))
          {
            if($senha == $confirmarSenha)
            {
              if($u->cadastrar($nome, $cpf, $email, $telefone, $senha, $cep, 
            $endereço, $numero, $complemento, $bairro, $cidade, $estado))
              {
                ?>
                <div id="msg-sucesso">
                Cadastrado com sucesso! Acesse para entrar!
                </div>
                <?php
              }

              else 
              {
                ?>
                <div class="msg-erro">
                E-mail já cadastrado!
                </div>
                <?php
              }
            }
            else
            {
              ?>
                <div class="msg-erro">
                Senhas não correspondem!
                </div>
                <?php
            }
            
          }
          else
          {
            ?>
            <div class="msg-erro">
            <?php echo "Erro: ".$u->msgErro; ?>
            </div>
            <?php
          }
        } else
        {
          ?>
                <div class="msg-erro">
                Preencha todos os campos!
                </div>
                <?php
        }
      }
?>

      <!-- <a class="top" href="">VOLTAR AO TOPO</button></a> -->

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
          <p>© 2024 DableuPro LTDA | Jacareí - São Paulo | Todos os Direitos Reservados.</p>
          <a class="logo-desen" href="https://www.mswebwork.com.br" target="_blank" rel="noopener noreferrer"><img src="IMG/Sem título.png" alt="logo do desenvolvedor"></a>
        </div>
    </footer>
        <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
        <script src="JAVASCRIPT/bootstrap.bundle.js"></script>
        <script src="https://kit.fontawesome.com/43b36f20b7.js" crossorigin="anonymous"></script>
        <script src="JAVASCRIPT/slick.min.js"></script>
        <script src="JAVASCRIPT/slick.js"></script>
        <script src="JAVASCRIPT/funcoes.js"></script>
  
    </body>
    </html>
