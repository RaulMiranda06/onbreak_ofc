<?php
session_start();
include("includes/conexao.php");
include("includes/header.php");

// Inicializa o carrinho se ainda não existir
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Adiciona um produto ao carrinho
if (isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Busca o produto no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM lanches WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($produto) {
        $estoque = intval($produto['estoque']);
        
        if (isset($_SESSION['cart'][$id])) {
            if ($_SESSION['cart'][$id]['quantidade'] < $estoque) {
                $_SESSION['cart'][$id]['quantidade']++;
            }
        } else {
            $_SESSION['cart'][$id] = [
                'nome' => htmlspecialchars($produto['nome']),
                'preco' => floatval($produto['preco']),
                'imagem' => htmlspecialchars($produto['imagem']),
                'quantidade' => 1,
                'estoque' => $estoque
            ];
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Atualiza a quantidade de itens no carrinho sem ultrapassar o estoque
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantidade'] as $id => $quantidade) {
        $id = intval($id);
        $quantidade = intval($quantidade);
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantidade'] = min($quantidade, $_SESSION['cart'][$id]['estoque']);
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Remove um item do carrinho com confirmação
if (isset($_GET['action']) && $_GET['action'] == "remove" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        img { width: 50px; height: 50px; object-fit: cover; }
        .btn { padding: 8px 12px; text-decoration: none; color: white; border-radius: 4px; display: inline-block; text-align: center; }
        .btn-remove { background: red; }
        .btn-finalizar { background: green; }
        .btn-back { background: blue; }
        .total-price { font-size: 20px; margin-top: 10px; font-weight: bold; }
        .quantity-input { width: 50px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seu Carrinho</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Estoque</th>
                            <th>Subtotal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $id => $item): 
                            $subtotal = $item["preco"] * $item["quantidade"];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><img src="uploads/<?php echo htmlspecialchars($item["imagem"]); ?>" alt="<?php echo htmlspecialchars($item["nome"]); ?>"></td>
                            <td><?php echo htmlspecialchars($item["nome"]); ?></td>
                            <td>R$ <?php echo number_format($item["preco"], 2, ',', '.'); ?></td>
                            <td>
                                <input type="number" name="quantidade[<?php echo $id; ?>]" class="quantity-input" 
                                    value="<?php echo $item["quantidade"]; ?>" min="1" max="<?php echo $item["estoque"]; ?>">
                            </td>
                            <td><?php echo isset($item["estoque"]) ? $item["estoque"] : 'Indisponível'; ?></td>
                            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                            <td>
                                <a href="carrinho.php?action=remove&id=<?php echo $id; ?>" class="btn btn-remove" 
                                    onclick="return confirm('Tem certeza que deseja remover este item?')">Remover</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="total-price">
                    Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
                </div>
                <br><br>
                <button type="submit" name="update_cart" class="btn btn-finalizar">Atualizar Carrinho</button>
            </form>
            <br>
            <a href="finalizar.php" class="btn btn-finalizar">Finalizar Compra</a>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>
        <br><br>
        <a href="index.php" class="btn btn-back">Continuar Comprando</a>
    </div>
    <?php include("includes/footer.php"); ?>
</body>
</html>
