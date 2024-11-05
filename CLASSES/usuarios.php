<?php

class Usuario
{
    private $pdo;
    public $msgErro = "";
    public function conectar ($nome, $host, $usuario, $senha)
    {
        global $pdo;
        global $msgErro;
        try {
            $pdo = new PDO("mysql:dbname=".$nome.";host=".$host,$usuario,$senha);
        } catch (PDOException $e) {
            $msgErro = $e-> getMessage();
        }
    }

    public function cadastrar ($nome, $cpf, $email, $telefone, $senha, $cep, 
    $endereço, $numero, $complemento, $bairro, $cidade, $estado)
    {
        global $pdo;
        
        $sql = $pdo->prepare("SELECT id_usuario FROM usuarios 
        WHERE email = :e");

        $sql->bindValue(":e",$email);
        $sql->execute();
        if($sql->rowCount () > 0)
        {
            return false;
        } 
        else {
            $sql = $pdo->prepare("INSERT INTO usuarios (nome, cpf, email, telefone,
            senha, cep, endereço, numero, complemento, bairro, cidade, estado) VALUES (:n, :c, :e, :t, :s, :z,
            :r, :h, :p, :b, :y, :f)");
            $sql->bindValue(":n",$nome);
            $sql->bindValue(":c",$cpf);
            $sql->bindValue(":e",$email);
            $sql->bindValue(":t",$telefone);
            $sql->bindValue(":s",md5($senha));
            $sql->bindValue(":z",$cep);
            $sql->bindValue(":r",$endereço);
            $sql->bindValue(":h",$numero);
            $sql->bindValue(":p",$complemento);
            $sql->bindValue(":b",$bairro);
            $sql->bindValue(":y",$cidade);
            $sql->bindValue(":f",$estado);
            $sql->execute();
            return true;
        }
    }


    public function logar ($email, $senha)
    {
        global $pdo;
        
        $sql = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e AND senha = :s");
        $sql->bindValue(":e",$email);
        $sql->bindValue(":s",md5($senha));
        $sql->execute();
        if ($sql->rowCount() > 0)
        {
            $dado = $sql->fetch();
            session_start();
            $_SESSION['id_usuario'] = $dado['id_usuario'];
            return true;
        }
        else {
            return false;
        }
    }
}


?>