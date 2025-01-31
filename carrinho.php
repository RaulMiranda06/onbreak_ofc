<?php
session_start(); // Inicia a sessão

// Inclui a conexão com o banco de dados
include("includes/conexao.php");
include('includes/header.php');

// Inicializa o carrinho, se ainda não estiver na sessão
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Verifica se a ação de adicionar ao carrinho foi chamada
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    // Verifica se o produto já está no carrinho
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1; // Incrementa a quantidade
    } else {
        $_SESSION['cart'][$product_id] = 1; // Adiciona o produto com quantidade 1
    }
}

// Função para calcular o total do carrinho
function calcular_total() {
    global $pdo;
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $quantidade) {
        $stmt = $pdo->prepare("SELECT preco FROM lanches WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $lanche = $stmt->fetch();
        if ($lanche) {
            $total += $lanche['preco'] * $quantidade; // Calcula o total do produto
        }
    }
    return $total;
}

// Verifica se o usuário deseja remover um item do carrinho
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    unset($_SESSION['cart'][$product_id]); // Remove o item do carrinho
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Carrinho de Compras</title>
    <style>
        /* Estilos para a página do carrinho */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .cart-container {
            padding: 20px;
            margin-top: 50px;
            width: 80%;
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-header {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart-item-name {
            font-size: 18px;
            flex: 1;
            margin-left: 10px;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            padding: 5px 10px;
            border: none;
            background-color: #1e88e5;
            color: white;
            cursor: pointer;
            margin-right: 5px;
            border-radius: 5px;
        }

        .btn-remove {
            background-color: #d32f2f;
            border-radius: 5px;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
        }

        .cart-total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 18px;
            margin-top: 30px;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="cart-container">
        <div class="cart-header">Carrinho de Compras</div>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Seu carrinho está vazio.</p>
        <?php else: ?>
            <div class="cart-items">
                <?php
                foreach ($_SESSION['cart'] as $product_id => $quantity):
                    // Busca os dados do produto
                    $stmt = $pdo->prepare("SELECT nome, preco, imagem FROM lanches WHERE id = :id");
                    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $lanche = $stmt->fetch();
                    if ($lanche):
                ?>
                    <div class="cart-item">
                        <div class="cart-item-img">
                            <img src="/uploads/<?php echo htmlspecialchars($lanche['imagem']); ?>" alt="<?php echo htmlspecialchars($lanche['nome']); ?>">
                        </div>
                        <div class="cart-item-name"><?php echo htmlspecialchars($lanche['nome']); ?></div>
                        <div class="cart-item-quantity">
                            <span>Quantidade: <?php echo $quantity; ?></span>
                            <a href="carrinho.php?action=remove&id=<?php echo $product_id; ?>" class="btn-remove">Remover</a>
                        </div>
                        <div class="cart-item-price">R$ <?php echo number_format($lanche['preco'] * $quantity, 2, ',', '.'); ?></div>
                    </div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>

            <div class="cart-total">
                Total: R$ <?php echo number_format(calcular_total(), 2, ',', '.'); ?>
            </div>

            <a href="finalizar-compra.php" class="checkout-btn">Finalizar Compra</a>
        <?php endif; ?>
    </div>

</body>
</html>

<?php include('includes/footer.php'); ?>
