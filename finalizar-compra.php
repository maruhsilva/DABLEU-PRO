<?php
// Configuração do Mercado Pago
require 'vendor/autoload.php';
MercadoPago\SDK::setAccessToken('APP_USR-7557293504970150-111823-6998573d94a7a1eeb3e93fcaf80617ec-50073279');

$json_input = file_get_contents('php://input');
echo $json_input; // Para ver o que está sendo recebido
$dados = json_decode($json_input, true); // Converte o JSON para array

// Verifica se o JSON foi recebido corretamente
if (!$dados) {
    die('Erro ao decodificar os dados do carrinho');
}

// Verifique se o campo 'carrinho' existe e é um array
$carrinho = isset($dados['carrinho']) ? $dados['carrinho'] : null;

if (!$carrinho || !is_array($carrinho)) {
    die('Carrinho vazio ou com dados inválidos');
}

// Criação de itens de preferência para o Mercado Pago
$itens = [];
foreach ($carrinho as $produto) {
    $item = new MercadoPago\Item();
    $item->title = $produto['nome'];
    $item->quantity = $produto['quantidade'];
    $item->unit_price = $produto['preco'];
    $item->picture_url = $produto['imagem'];
    $itens[] = $item;
}

// Criação da preferência
$preference = new MercadoPago\Preference();
$preference->items = $itens;
$preference->back_urls = array(
    "success" => "https://www.suaurl.com.br/sucesso",
    "failure" => "https://www.suaurl.com.br/erro",
    "pending" => "https://www.suaurl.com.br/pendente"
);
$preference->auto_return = "approved";
$preference->save();

// Retorna a URL de pagamento para o frontend
echo json_encode(array("redirect_url" => $preference->init_point));
?>
