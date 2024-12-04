<?php
require_once __DIR__ . 'usuario.php';

$nomeBanco = 'login_dableu';      // Nome do banco de dados
$host = 'login_dableu.mysql.dbaas.com.br';           // Endereço do host (ex.: localhost ou IP do servidor)
$usuarioBanco = 'login_dableu'; // Usuário do banco de dados
$senhaBanco = 'Marua3902@';     // Senha do banco de dados

// Instanciar a classe Usuario
$usuario = new usuario();

// Conectar ao banco de dados
if (!$usuario->conectar($nomeBanco, $host, $usuarioBanco, $senhaBanco)) {
    die("Erro ao conectar com o banco de dados: " . $usuario->msgErro);
}

// A conexão já está ativa na instância da classe $usuario.
?>