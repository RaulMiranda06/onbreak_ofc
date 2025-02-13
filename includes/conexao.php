<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'sistema_lanche';
$username = 'root';
$password = '';

$stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, total, metodo_pagamento, status) VALUES (?, ?, ?, ?)");
$stmt->execute([$cliente_id, $total, $metodo_pagamento, 'pago']);
$pedido_id = $pdo->lastInsertId(); // ID do pedido recém-inserido

// Inserir itens do pedido
foreach ($_SESSION['cart'] as $id => $item) {
    $subtotal = $item['preco'] * $item['quantidade'];
    $stmt = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$pedido_id, $id, $item['quantidade'], $item['preco'], $subtotal]);
}

// Atualizar o estoque
foreach ($_SESSION['cart'] as $id => $item) {
    $novo_estoque = $item['estoque'] - $item['quantidade'];
    $stmt = $pdo->prepare("UPDATE lanches SET estoque = ? WHERE id = ?");
    $stmt->execute([$novo_estoque, $id]);
}

// Limpar o carrinho
unset($_SESSION['cart']);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>


