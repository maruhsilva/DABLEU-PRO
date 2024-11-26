<?php
require __DIR__ .  '/vendor/autoload.php';

// Configuração da API do Mercado Pago
MercadoPago\SDK::setAccessToken('APP_USR-7557293504970150-111823-6998573d94a7a1eeb3e93fcaf80617ec-50073279');

// Coletar dados enviados pelo frontend
$data = json_decode(file_get_contents('php://input'), true);

// Criação do objeto de preferência
$preference = new MercadoPago\Preference();

// Adicionar os itens
$items = [];

foreach ($data['items'] as $item) {
    $produto = new MercadoPago\Item();
    $produto->title = $item['title'];
    $produto->quantity = $item['quantity'];
    $produto->unit_price = $item['unit_price'];

    // Adiciona cada item ao array
    $items[] = $produto;
}

// Define os itens na preferência
$preference->items = $items;

// Salva a preferência
try {
    $preference->save();
    $response = [
        'success' => true,
        'redirect_url' => $preference->init_point // URL de redirecionamento para o pagamento
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Erro ao processar a compra: ' . $e->getMessage()
    ];
}

// Retorna a resposta em formato JSON
header('Content-Type: application/json');
echo json_encode($response);

try {
    $preference->save();
    $response = [
        'success' => true,
        'redirect_url' => $preference->init_point
    ];
} catch (Exception $e) {
    error_log('Erro ao salvar a preferência: ' . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Erro ao processar a compra: ' . $e->getMessage()
    ];
}
?>
