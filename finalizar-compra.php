<?php
session_start();
require_once 'vendor/autoload.php';

// Configura o Mercado Pago
MercadoPago\SDK::setAccessToken('APP_USR-7557293504970150-111823-6998573d94a7a1eeb3e93fcaf80617ec-50073279');

// Verifica se há dados no carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrinho'])) {
    $carrinho = json_decode($_POST['carrinho'], true);

    if (empty($carrinho)) {
        die('O carrinho está vazio.');
    }

    // Criar a preferência de pagamento
    $preference = new MercadoPago\Preference();

    // Configurar os itens da compra
    $items = [];
    foreach ($carrinho as $produto) {
        $item = new MercadoPago\Item();
        $item->title = $produto['nome']; // Nome do produto
        $item->quantity = $produto['quantidade']; // Quantidade
        $item->unit_price = $produto['preco']; // Preço unitário
        $items[] = $item;
    }
    $preference->items = $items;

    // Configurar o comprador
    $preference->payer = [
        "name" => $_SESSION['nome_usuario'], // Nome do cliente
        "email" => $_SESSION['email_usuario'], // E-mail do cliente
    ];

    // URL de redirecionamento após pagamento
    $preference->back_urls = [
        "success" => "http://seusite.com/sucesso.php",
        "failure" => "http://seusite.com/erro.php",
        "pending" => "http://seusite.com/pendente.php"
    ];
    $preference->auto_return = "approved";

    // Salvar a preferência
    $preference->save();

    // Redirecionar para a página de pagamento do Mercado Pago
    header("Location: " . $preference->init_point);
    exit;
} else {
    die('Dados inválidos. Retorne ao carrinho.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['carrinho'])) {
        echo '<pre>';
        print_r(json_decode($_POST['carrinho'], true));
        echo '</pre>';
    } else {
        echo 'Carrinho não enviado.';
    }
} else {
    echo 'Requisição inválida.';
}
exit;
?>