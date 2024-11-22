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
        $endereco = addslashes($_POST['endereco']);
        $numero = addslashes($_POST['numero']);
        $complemento = addslashes($_POST['complemento']);
        $bairro = addslashes($_POST['bairro']);
        $cidade = addslashes($_POST['cidade']);
        $estado = addslashes($_POST['estado']);

        if(!empty($nome) && !empty($cpf) && !empty($email) && !empty($telefone) && !empty($senha) 
        && !empty($confirmarSenha) && !empty($cep) && !empty($endereco) && !empty($numero) && !empty($complemento) &&
        !empty($bairro) && !empty($cidade) && !empty($estado))
        {
          $u->conectar("login_dableupro", "localhost", "root", "");
          if ($u->$msgErro == "")
          {
            if($senha == $confirmarSenha)
            {
              if($u->cadastrar($nome, $cpf, $email, $telefone, $senha, $cep, 
            $endereco, $numero, $complemento, $bairro, $cidade, $estado))
              {
                echo "Cadastrado com sucesso! Acesse para entrar!";
              }

              else 
              {
                echo "Email Já Cadastrado!";
              }
            }
            else
            {
              echo "Senhas não correspondem!";
            }
            
          }
          else
          {
            echo "Erro: ".$u->msgErro;
          }
        } else
        {
          echo "Preencha todos os campos!";
        }
      }
?>