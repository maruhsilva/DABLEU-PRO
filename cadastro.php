<?php

$u = new Usuario();
if (!$u->conectar("login_dableu", "login_dableu.mysql.dbaas.com.br", "login_dableu", "Marua3902@")) {
    die("Erro ao conectar com o banco: " . $u->msgErro);
} else {
    echo "Conexão bem-sucedida!<br>";
}

if (isset($_POST['nome'])) {
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

    if (!empty($nome) && !empty($cpf) && !empty($email) && !empty($telefone) && !empty($senha) 
        && !empty($confirmarSenha) && !empty($cep) && !empty($endereco) && !empty($numero) 
        && !empty($bairro) && !empty($cidade) && !empty($estado)) {
        
        // Conecte-se ao banco
        if ($u->conectar($nomeBanco, $host, $usuarioBanco, $senhaBanco)) {
            if ($u->msgErro == "") {
                if ($senha == $confirmarSenha) {
                    if ($u->cadastrar($nome, $cpf, $email, $telefone, $senha, $cep, 
                        $endereco, $numero, $complemento, $bairro, $cidade, $estado)) {
                        echo "Cadastrado com sucesso!";
                    } else {
                        echo "Email já cadastrado!";
                    }
                } else {
                    echo "Senhas não correspondem!";
                }
            } else {
                echo "Erro: " . $u->msgErro;
            }
        } else {
            echo "Erro ao conectar com o banco!";
        }
    } else {
        echo "Preencha todos os campos!";
    }
}
?>
