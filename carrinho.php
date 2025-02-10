<?php
session_start(); // Inicia a sessão

// Inclui a conexão com o banco de dados
include("includes/conexao.php");
include('includes/header.php');

// Inicializa o carrinho caso não exista na sessão
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Verificar as ações (adicionar, atualizar, remover)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $lanche_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Garantir que o ID seja um número inteiro

    // Adiciona um item ao carrinho
    if ($action == 'add') {
        if (isset($_SESSION['cart'][$lanche_id])) {
            $_SESSION['cart'][$lanche_id]++;
        } else {
            $_SESSION['cart'][$lanche_id] = 1;
        }
    }

    // Atualiza a quantidade de um item no carrinho
    if ($action == 'update' && isset($_GET['quantity'])) {
        $quantity = (int)$_GET['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$lanche_id] = $quantity;
        }
    }

    // Remove um item do carrinho
    if ($action == 'remove') {
        unset($_SESSION['cart'][$lanche_id]);
    }

    // Redireciona para o carrinho após a ação
    header("Location: carrinho.php");
    exit;
}

// Query para pegar os dados dos lanches
$query = "SELECT * FROM lanches";
$stmt = $pdo->query($query); // Executa a query
$lanches = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usar PDO::FETCH_ASSOC para maior clareza
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>

    <!-- CSS Interno -->
    <style>
        /* Reset de estilos básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Corpo da página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Container do carrinho */
        .cart-container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Estilos dos itens do carrinho */
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .cart-item img {
            max-width: 150px;
            margin-right: 20px;
            border-radius: 8px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-info h3 {
            font-size: 1.2em;
            color: #444;
        }

        .cart-item-info p {
            font-size: 1em;
            color: #666;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .quantity-controls a {
            text-decoration: none;
            background-color: #e53935;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 1.2em;
            margin: 0 5px;
        }

        .quantity-controls input {
            width: 50px;
            text-align: center;
            font-size: 1.2em;
            padding: 5px;
            margin: 0 10px;
        }

        /* Estilo do botão Remover */
        .btn-remove {
            text-decoration: none;
            color: white;
            background-color: #e53935;
            padding: 8px 16px;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #c62828;
        }

        /* Estilo para o total do carrinho */
        .total-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.5em;
            margin-top: 20px;
        }

        .total-price p {
            font-weight: bold;
            color: #333;
        }

        .total-price .btn-remove {
            background-color: #4CAF50;
            font-size: 1.2em;
            padding: 10px 20px;
        }

        .total-price .btn-remove:hover {
            background-color: #388E3C;
        }

        /* Botão continuar comprando */
        .btn-continue {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #00bcd4;
            color: white;
            border-radius: 4px;
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-continue:hover {
            background-color: #0097a7;
        }

        /* Media Query para telas pequenas */
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-item img {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .cart-item-info {
                margin-left: 0;
            }

            .quantity-controls {
                flex-direction: column;
                align-items: flex-start;
            }

            .quantity-controls input {
                margin: 5px 0;
            }

            .total-price {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

</head>
<body>

<div class="cart-container">
    <h2>Seu Carrinho de Compras</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <?php 
        $total = 0;
        foreach ($_SESSION['cart'] as $lanche_id => $quantity):
            // Busca os dados do lanche no banco de dados
            $stmt = $pdo->prepare("SELECT * FROM lanches WHERE id = :id");
            $stmt->bindParam(':id', $lanche_id, PDO::PARAM_INT);
            $stmt->execute();
            $lanche = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar se o produto foi encontrado
            if ($lanche) {
                // Calculando o total do item
                $item_total = $lanche['preco'] * $quantity;
                $total += $item_total;
        ?>
        <div class="cart-item">
            <img src="/uploads/<?php echo $lanche['imagem'] ?: 'default.png'; ?>" alt="Imagem do produto">
            <div class="cart-item-info">
                <h3><?php echo htmlspecialchars($lanche['nome']); ?></h3>
                <p>Preço Unitário: R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></p>
                <div class="quantity-controls">
                    <a href="carrinho.php?action=update&id=<?php echo $lanche['id']; ?>&quantity=<?php echo max(1, $quantity - 1); ?>" class="btn-remove">-</a>
                    <input type="number" value="<?php echo $quantity; ?>" min="1" onchange="window.location.href='carrinho.php?action=update&id=<?php echo $lanche['id']; ?>&quantity=' + this.value">
                    <a href="carrinho.php?action=update&id=<?php echo $lanche['id']; ?>&quantity=<?php echo $quantity + 1; ?>" class="btn-remove">+</a>
                </div>
                <p>Total: R$ <?php echo number_format($item_total, 2, ',', '.'); ?></p>
            </div>
            <a href="carrinho.php?action=remove&id=<?php echo $lanche['id']; ?>" class="btn-remove">Remover</a>
        </div>
        <?php
            } else {
                // Caso o produto não seja encontrado
                echo "<p>Produto não encontrado.</p>";
            }
        endforeach;
        ?>

        <div class="total-price">
            <p>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
            <a href="finalizar_compra.php" class="btn-remove">Finalizar Compra</a>
        </div>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
    <?php endif; ?>

    <a href="index.php" class="btn-continue">Continuar Comprando</a>

</div>
<br><br><br><br>
<?php include('includes/footer.php'); ?>

</body>
</html>
