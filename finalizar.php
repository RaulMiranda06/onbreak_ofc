<?php 
session_start();
include("includes/conexao.php");
include("includes/header.php");

// Verifica se o carrinho tem produtos
if (empty($_SESSION['cart'])) {
    header("Location: carrinho.php?msg=" . urlencode("Seu carrinho está vazio!"));
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Calcula o total do pedido
    $total = array_sum(array_map(fn($item) => $item['preco'] * $item['quantidade'], $_SESSION['cart']));
    
    // Insere na tabela pedidos
    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, data, total) VALUES (?, NOW(), ?)");
    $stmt->execute([$_SESSION['usuario_id'], $total]);
    $pedido_id = $pdo->lastInsertId();
    
    // Insere os itens do pedido
    foreach ($_SESSION['cart'] as $id => $item) {
        $stmt = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, lanche_id, quantidade, preco) VALUES (?, ?, ?, ?)");
        $stmt->execute([$pedido_id, $id, $item['quantidade'], $item['preco']]);
        
        // Atualiza o estoque
        $stmt = $pdo->prepare("UPDATE lanches SET estoque = estoque - ? WHERE id = ?");
        $stmt->execute([$item['quantidade'], $id]);
    }
    
    $pdo->commit();
    $_SESSION['cart'] = []; // Limpa o carrinho
    
    // Exibe mensagem de sucesso
    echo "<h2>Pedido Finalizado com Sucesso!</h2>";
    echo "<p>O número do seu pedido é: <strong>#" . htmlspecialchars($pedido_id) . "</strong></p>";
    echo "<p>O valor total do pedido foi: <strong>R$ " . number_format($total, 2, ',', '.') . "</strong></p>";
    echo "<a href='index.php'>Voltar para a Loja</a>";
    exit;
    
} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: carrinho.php?msg=" . urlencode("Erro ao finalizar a compra: " . $e->getMessage()));
    exit;
}
?>
