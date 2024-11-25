<?php

$dbhost = '185.27.134.202';
$dbusuario = 'if0_37766185';
$dbsenha = 'Marua3902';
$dbnome = 'if0_37766185_login_dableupro';

        // $dbhost = 'Localhost';
        // $dbusuario = 'root';
        // $dbsenha = '';
        // $dbnome = 'login_dableupro';


// $dbhost = 'sql313.byethost3.com';
// $dbusuario = 'b3_37767395';
// $dbsenha = 'marua3902@';
// $dbnome = 'b3_37767395_login_dableupro';
    
    $mysqli = new mysqli($dbhost, $dbusuario, $dbsenha, $dbnome);

    if ($mysqli->connect_errno) 
    { 
        echo "Não Conectado: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; 
    }

    mysqli_set_charset($conexao, "utf8");
?>