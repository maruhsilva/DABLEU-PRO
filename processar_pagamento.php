<?php
require __DIR__ . '/vendor/autoload.php'; // Carrega o Mercado Pago

// Configurar o acesso ao Mercado Pago
MercadoPago\SDK::setAccessToken('APP_USR-4793205581620291-112211-828e7008b13c17b4b80b583f0303137c-50073279');

// Capturar os itens enviados pelo frontend
$data = json_decode(file_get_contents("php://input"), true);
$items = $data['items'] ?? [];

// Validar itens recebidos
if (empty($items)) {
    echo json_encode(['success' => false, 'error' => 'Carrinho vazio ou inválido.']);
    exit;
}

// Criar os itens para o Mercado Pago
$preference = new MercadoPago\Preference();
$itemList = [];

foreach ($items as $item) {
    $mercadoPagoItem = new MercadoPago\Item();
    $mercadoPagoItem->title = $item['title'];
    $mercadoPagoItem->quantity = $item['quantity'];
    $mercadoPagoItem->unit_price = $item['unit_price'];
    $itemList[] = $mercadoPagoItem;
}

$preference->items = $itemList;

// URL de retorno após o pagamento
$preference->back_urls = [
    "success" => "http://seusite.com/sucesso.php",
    "failure" => "http://seusite.com/erro.php",
    "pending" => "http://seusite.com/pendente.php"
];
$preference->auto_return = "approved";

// Salvar a preferência no Mercado Pago
try {
    $preference->save();
    echo json_encode([
        'success' => true,
        'redirect_url' => $preference->init_point // URL para redirecionar ao Mercado Pago
    ]);
} catch (Exception $e) {
    error_log("Erro ao criar preferência de pagamento: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro ao criar a preferência de pagamento.']);
}
