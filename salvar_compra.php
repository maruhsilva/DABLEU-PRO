<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

// Configurações do banco de dados
$host = 'login_dableu.mysql.dbaas.com.br';
$dbname = 'login_dableu';
$user = 'login_dableu';
$password = 'Marua3902@';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o pagamento foi aprovado
    if (!isset($_GET['status']) || $_GET['status'] !== 'approved') {
        echo "Pagamento não aprovado ou status inválido.";
        exit;
    }

    // Obtém informações da URL
    $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;
    if (!$id_usuario) {
        echo "Usuário não identificado.";
        exit;
    }

    // Verifica se o carrinho contém itens
    $itens = $_SESSION['carrinho'] ?? [];
    if (empty($itens)) {
        echo "Carrinho vazio.";
        exit;
    }

    // Obtém dados do pagamento do Mercado Pago
    $payment_id = $_GET['payment_id'];
    $total = $_GET['transaction_amount'];

    // Inicia transação
    $pdo->beginTransaction();

    // Salva pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, total, payment_id) VALUES (:id_usuario, :total, :payment_id)");
    $stmt->execute([':id_usuario' => $id_usuario, ':total' => $total, ':payment_id' => $payment_id]);
    $id_pedido = $pdo->lastInsertId();

    // Salva itens do pedido
    $stmt = $pdo->prepare("INSERT INTO itens_pedido (id_pedido, nome_produto, quantidade, preco) VALUES (:id_pedido, :nome_produto, :quantidade, :preco)");
    foreach ($itens as $item) {
        $stmt->execute([
            ':id_pedido' => $id_pedido,
            ':nome_produto' => $item['title'],
            ':quantidade' => $item['quantity'],
            ':preco' => $item['unit_price']
        ]);
    }

    // Confirma a transação
    $pdo->commit();

    // Limpa o carrinho da sessão após o sucesso
    unset($_SESSION['carrinho']);

    echo "Pedido salvo com sucesso!";

} catch (Exception $e) {
    // Rola de volta em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Registra o erro
    error_log($e->getMessage(), 3, 'error_log.txt');
    echo "Erro ao salvar pedido: " . $e->getMessage();
}
?>
