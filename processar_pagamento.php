<?php
session_start();
session_regenerate_id();
require_once 'classes/config.php'; // Inclui a conexão com o banco de dados
require 'vendor/autoload.php'; // Carregar o autoloader do Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodo_pagamento'])) {
    header("Location: processar_pagamento.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['metodo_pagamento'])) {
    // Redireciona de volta para a página do carrinho
    header("Location: carrinho-logado.php");
    exit;
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: user-login.php');
    exit;
}

// Verifica se o carrinho foi enviado
if (isset($_POST["carrinho"])) {
    $carrinho = json_decode($_POST["carrinho"], true);

    // Verifica se o carrinho está vazio
    if (empty($carrinho)) {
        $_SESSION['mensagem'] = 'Seu carrinho está vazio. Adicione produtos antes de prosseguir.';
        header('Location: carrinho-logado.php'); // Mantém o usuário na página de carrinho
        exit;
    }
} else {
    $_SESSION['mensagem'] = 'Carrinho não enviado. Por favor, tente novamente.';
    header('Location: carrinho-logado.php'); // Mantém o usuário na página de carrinho
    exit;
}

// Prosseguir com o processamento do pagamento somente se houver produtos

// Recebe o método de pagamento
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';

// Dados do cliente (da sessão)
$id_usuario = $_SESSION['id_usuario'];

// Consulta para pegar os dados do cliente
$sql = "SELECT nome, cpf, telefone, endereco, numero, cep FROM usuarios WHERE id_usuario = '$id_usuario'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
    $nome_cliente = $cliente['nome'];
    $cpf_cliente = $cliente['cpf'];
    $telefone_cliente = $cliente['telefone'];
    $endereco_cliente = $cliente['endereco'];
    $numero_cliente = $cliente['numero'];
    $cep_cliente = $cliente['cep'];
} else {
    echo "Erro ao recuperar os dados do cliente.";
    exit;
}

// Dados do pagamento
$produtos = [];
foreach ($carrinho as $item) {
    // Verifica se os dados do item são válidos antes de adicionar
    if (isset($item['nome'], $item['quantidade'], $item['preco'])) {
        $produtos[] = [
            'title' => $item['nome'],
            'quantity' => $item['quantidade'],
            'unit_price' => $item['preco'],
        ];
    } else {
        echo "Dados do item inválidos.";
        exit;
    }
}

// Configura a API do Mercado Pago
MercadoPago\SDK::setAccessToken('APP_USR-7557293504970150-111823-6998573d94a7a1eeb3e93fcaf80617ec-50073279'); // Substitua pelo seu token de acesso

// Criação da preferência
$preference = new MercadoPago\Preference();
$preference->items = $produtos;

// Se for pagamento com cartão
if ($metodo_pagamento == 'cartao') {
    $preference->back_urls = [
        "success" => "localhost/dableu-pro/carrinho.html",
        "failure" => "localhost/dableu-pro/carrinho.html",
        "pending" => "localhost/dableu-pro/carrinho.html"
    ];
    $preference->auto_return = "approved";
} elseif ($metodo_pagamento == 'pix') {
    // Configuração para pagamento via Pix
    $preference->back_urls = [
        "success" => "localhost/dableu-pro/carrinho.html",
        "failure" => "localhost/dableu-pro/carrinho.html",
        "pending" => "localhost/dableu-pro/carrinho.html"
    ];
    $preference->auto_return = "approved";
}

// Cria a preferência de pagamento
$preference->save();

// Redireciona para o Mercado Pago
header("Location: " . $preference->init_point);
exit;
