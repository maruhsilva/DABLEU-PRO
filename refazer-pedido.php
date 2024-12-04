<?php
session_start();
ob_start();
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';
MercadoPago\SDK::setAccessToken('TEST-7557293504970150-111823-b70f77389318ae03320e08bd19dd8afa-50073279');

// Configuração do banco de dados
$host = 'login_dableu.mysql.dbaas.com.br';
$db = 'login_dableu';
$user = 'login_dableu';
$password = 'Marua3902@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
    ob_end_flush();
    exit;
}

$id_pedido = isset($_GET['id_pedido']) ? $_GET['id_pedido'] : null;

if (!$id_pedido) {
    echo json_encode(['success' => false, 'message' => 'Pedido não encontrado.']);
    ob_end_flush();
    exit;
}

// Buscar o pedido com o id_pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido");
$stmt->execute(['id_pedido' => $id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo json_encode(['success' => false, 'message' => 'Pedido não encontrado.']);
    ob_end_flush();
    exit;
}

// Buscar os itens associados ao pedido
$stmt_itens = $pdo->prepare("SELECT * FROM itens_pedido WHERE id_pedido = :id_pedido");
$stmt_itens->execute(['id_pedido' => $id_pedido]);
$itens_pedido = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

if (!$itens_pedido) {
    echo json_encode(['success' => false, 'message' => 'Itens do pedido não encontrados.']);
    ob_end_flush();
    exit;
}

// Verificar o status do pagamento do pedido
$status = $pedido['status']; // Certifique-se de que o campo 'status' existe na tabela 'pedidos'

if ($status == 'aprovado') {
    echo json_encode(['success' => false, 'message' => 'O pagamento já foi aprovado. Não é possível refazê-lo.']);
    ob_end_flush();
    exit;
}

try {
    // Aqui começa o processo de refazer o pagamento (como já explicado antes)
    // Atualize a lógica para lidar com o pagamento novamente, se necessário

    // Exemplo de chamada para refazer o pagamento
    $preference = new MercadoPago\Preference();
    $items = [];  // Seus itens de pagamento

    // Preenchendo os itens
    foreach ($itens_pedido as $item) {
        $produto = new MercadoPago\Item();
        $produto->title = $item['nome_produto'];
        $produto->quantity = $item['quantidade'];
        $produto->unit_price = $item['preco'];
        $items[] = $produto;
    }

    $preference->items = $items;

    $preference->back_urls = [
        "success" => "http://localhost/DABLEU-PRO/retorno-pagamento.php?id_usuario={$pedido['id_usuario']}&id_pedido={$id_pedido}",
        "failure" => "http://localhost/DABLEU-PRO/retorno-pagamento.php",
        "pending" => "http://localhost/DABLEU-PRO/retorno-pagamento.php"
    ];

    $preference->auto_return = "approved";
    $preference->payment_methods = array(
        'installments' => 10,
        'excluded_payment_types' => array(
            array("id" => "atm")
        ),
        'default_installments' => 3
    );

    $preference->save();

    // Se o pagamento foi recriado com sucesso
    $response = [
        'success' => true,
        'redirect_url' => $preference->init_point
    ];

    // Redireciona o usuário para o Mercado Pago
    header("Location: " . $response['redirect_url']);
    ob_end_flush();
    exit;

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Erro ao processar o pagamento: ' . $e->getMessage()
    ];
    echo json_encode($response);
    ob_end_flush();
}
?>
