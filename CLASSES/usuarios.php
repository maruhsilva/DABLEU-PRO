<?php

class Usuario
{
    private $pdo;
    public $msgErro = "";

    public function conectar($nome, $host, $usuario, $senha)
    {
        try {
            $this->pdo = new PDO("mysql:dbname=" . $nome . ";host=" . $host, $usuario, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar exceções para erros PDO
        } catch (PDOException $e) {
            $this->msgErro = $e->getMessage();
            return false;
        }
        return true;
    }

    public function cadastrar($nome, $cpf, $email, $telefone, $senha, $cep, $endereco, $numero, $complemento, $bairro, $cidade, $estado)
    {
        try {
            // Verificar se o email já existe
            $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e");
            $sql->bindValue(":e", $email);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                return false;
            }

            // Inserir novo usuário
            $sql = $this->pdo->prepare("INSERT INTO usuarios (nome, cpf, email, telefone, senha, cep, endereco, numero, complemento, bairro, cidade, estado) 
            VALUES (:n, :c, :e, :t, :s, :z, :r, :h, :p, :b, :y, :f)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":c", $cpf);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":s", $senha); // A senha deve ser criptografada
            $sql->bindValue(":z", $cep);
            $sql->bindValue(":r", $endereco);
            $sql->bindValue(":h", $numero);
            $sql->bindValue(":p", $complemento);
            $sql->bindValue(":b", $bairro);
            $sql->bindValue(":y", $cidade);
            $sql->bindValue(":f", $estado);
            $sql->execute();

            return true;

        } catch (PDOException $e) {
            $this->msgErro = $e->getMessage();
            return false;
        }
    }

    public function logar($email, $senha)
    {
        try {
            $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e AND senha = :s");
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", $senha); // A senha deve ser criptografada
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $dado = $sql->fetch();
                session_start();
                $_SESSION['id_usuario'] = $dado['id_usuario'];
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            $this->msgErro = $e->getMessage();
            return false;
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
?>
