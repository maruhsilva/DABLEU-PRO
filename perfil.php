<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

include 'Usuario.php';
$usuario = new Usuario();

// Conectar ao banco de dados
$usuario->conectar('nome_do_banco', 'localhost', 'usuario_banco', 'senha_banco');

// Verificar se a conexão foi bem-sucedida
if ($usuario->msgErro != "") {
    die("Erro ao conectar ao banco: " . $usuario->msgErro);
}

// Recuperar dados do usuário
$id_usuario = $_SESSION['id_usuario'];
$sql = $usuario->getPdo()->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
$sql->bindValue(":id", $id_usuario);
$sql->execute();
$dados = $sql->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar dados do usuário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $sql = $usuario->getPdo()->prepare("UPDATE usuarios SET nome = :n, cpf = :c, email = :e, telefone = :t,
    senha = :s, cep = :z, endereco = :r, numero = :h, complemento = :p, bairro = :b, cidade = :y, estado = :f 
    WHERE id_usuario = :id");

    $sql->bindValue(":n", $nome);
    $sql->bindValue(":c", $cpf);
    $sql->bindValue(":e", $email);
    $sql->bindValue(":t", $telefone);
    $sql->bindValue(":s", md5($senha)); // Senha criptografada
    $sql->bindValue(":z", $cep);
    $sql->bindValue(":r", $endereco);
    $sql->bindValue(":h", $numero);
    $sql->bindValue(":p", $complemento);
    $sql->bindValue(":b", $bairro);
    $sql->bindValue(":y", $cidade);
    $sql->bindValue(":f", $estado);
    $sql->bindValue(":id", $id_usuario);

    if ($sql->execute()) {
        echo "<script>alert('Dados atualizados com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao atualizar os dados.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
</head>
<body>
    <h1>Meu Perfil</h1>

    <form action="perfil.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?= $dados['nome']; ?>" required><br>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" id="cpf" value="<?= $dados['cpf']; ?>" required><br>

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="<?= $dados['email']; ?>" required><br>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" value="<?= $dados['telefone']; ?>" required><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" value=""><br>

        <label for="cep">CEP:</label>
        <input type="text" name="cep" id="cep" value="<?= $dados['cep']; ?>" required><br>

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" value="<?= $dados['endereco']; ?>" required><br>

        <label for="numero">Número:</label>
        <input type="text" name="numero" id="numero" value="<?= $dados['numero']; ?>" required><br>

        <label for="complemento">Complemento:</label>
        <input type="text" name="complemento" id="complemento" value="<?= $dados['complemento']; ?>"><br>

        <label for="bairro">Bairro:</label>
        <input type="text" name="bairro" id="bairro" value="<?= $dados['bairro']; ?>" required><br>

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" id="cidade" value="<?= $dados['cidade']; ?>" required><br>

        <label for="estado">Estado:</label>
        <input type="text" name="estado" id="estado" value="<?= $dados['estado']; ?>" required><br>

        <button type="submit">Salvar Alterações</button>
    </form>

</body>
</html>
