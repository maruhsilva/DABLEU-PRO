<?php
// Função para salvar os dados no banco de dados (supondo que você já tenha a conexão)
function salvarPagamento($payment_data) {
    try {
        // Conectar ao banco de dados
        $conn = new PDO('mysql:host=localhost;dbname=login_dableupro', 'root', '');

        // Definir o modo de erro PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar e executar o SQL
        $stmt = $conn->prepare("INSERT INTO pagamentos (payment_id, collection_id, amount, status, payer_email) 
            VALUES (:payment_id, :collection_id, :amount, :status, :payer_email)");
        
        $stmt->bindParam(':payment_id', $payment_data['payment_id']);
        $stmt->bindParam(':collection_id', $payment_data['collection_id']);
        $stmt->bindParam(':amount', $payment_data['amount']);
        $stmt->bindParam(':status', $payment_data['status']);
        $stmt->bindParam(':payer_email', $payment_data['payer_email']);
        
        // Executar a query
        $stmt->execute();

        echo "Dados do pagamento salvos com sucesso.";
    } catch (PDOException $e) {
        echo "Erro ao salvar os dados: " . $e->getMessage();
    }
}

// Salvar os dados recebidos da consulta
if (isset($response['payment_data']) && !empty($response['payment_data'])) {
    salvarPagamento($response['payment_data']);
}
?>
