<?php
session_start();
session_regenerate_id();
require_once 'classes/config.php'; 
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['metodo_pagamento'], $_POST['carrinho'])) {
    header("Location: carrinho-logado.php");
    exit;
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: user-login.php');
    exit;
}

// Valida e decodifica o carrinho
$carrinho = json_decode($_POST['carrinho'], true);
if (!$carrinho || empty($carrinho)) {
    $_SESSION['mensagem'] = 'Seu carrinho está vazio. Adicione produtos antes de prosseguir.';
    header('Location: carrinho-logado.php');
    exit;
}

// Valida o método de pagamento
$metodos_validos = ['cartao', 'pix'];
$metodo_pagamento = $_POST['metodo_pagamento'];
if (!in_array($metodo_pagamento, $metodos_validos)) {
    $_SESSION['mensagem'] = 'Método de pagamento inválido.';
    header('Location: carrinho-logado.php');
    exit;
}

// Obtem dados do cliente com prepared statements
$id_usuario = $_SESSION['id_usuario'];
$stmt = $mysqli->prepare("SELECT nome, cpf, telefone, endereco, numero, cep FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['mensagem'] = 'Erro ao recuperar os dados do cliente.';
    header('Location: carrinho-logado.php');
    exit;
}

$cliente = $result->fetch_assoc();

// Prepara os itens para o Mercado Pago
$produtos = [];
foreach ($carrinho as $item) {
    if (!isset($item['nome'], $item['quantidade'], $item['preco'])) {
        $_SESSION['mensagem'] = 'Carrinho com dados inválidos.';
        header('Location: carrinho-logado.php');
        exit;
    }
    $produtos[] = [
        'title' => htmlspecialchars($item['nome']),
        'quantity' => (int)$item['quantidade'],
        'unit_price' => (float)$item['preco'],
    ];
}

// Configuração do Mercado Pago
MercadoPago\SDK::setAccessToken(getenv('APP_USR-7557293504970150-111823-6998573d94a7a1eeb3e93fcaf80617ec-50073279')); 

$preference = new MercadoPago\Preference();
$preference->items = $produtos;
$preference->back_urls = [
    "success" => "http://localhost/dableu-pro/sucesso.php",
    "failure" => "http://localhost/dableu-pro/falha.php",
    "pending" => "http://localhost/dableu-pro/pendente.php"
];
$preference->auto_return = "approved";
$preference->save();

// Redireciona para o link de pagamento
header("Location: " . $preference->init_point);
exit;
