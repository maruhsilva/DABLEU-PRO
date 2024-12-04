<?php 

$host = 'login_dableu.mysql.dbaas.com.br';
$usuario = 'login_dableu';
$senha = 'Marua3902@';
$banco = 'login_dableu';


$conecta = new mysqli($host, $usuario, $senha, $banco);

if ($conecta->connect_error) {
    die("Falha na conexão: " . $conecta->connect_error);
} else {
    echo "Conexão OK!";
}

?>
