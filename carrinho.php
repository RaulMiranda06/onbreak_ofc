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
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 90%; max-width: 1200px; margin: auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        
        h1 { text-align: center; color: #333; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #008CBA; color: white; }
        td { background-color: #fafafa; }
        .imagem { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }

        .quantity-input {
            width: 60px;
            padding: 5px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            padding: 8px 12px;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 14px;
        }

        .btn-remove { background-color: #f44336; }
        .btn-remove:hover { background-color: #e53935; }

        .total-price { font-size: 20px; margin-top: 20px; font-weight: bold; text-align: right; }

        /* Estilos para os botões */
        .botaoatualizar {
            background-color: #4CAF50; /* Novo cor de fundo (verde) */
            color: white;
            padding: 12px 24px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-right: 10px;
        }

        .botaoatualizar:hover {
            background-color: #45a049; /* Cor do fundo ao passar o mouse */
        }

        .button {
            background-color: #008CBA;
            color: white;
            padding: 12px 24px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #007bb5;
        }

        .botaovoltar {
            background-color: #f44336;
            color: white;
            padding: 12px 24px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .botaovoltar:hover {
            background-color: #e53935;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            table, .total-price, .btn {
                font-size: 12px;
            }

            .quantity-input {
                width: 45px;
            }
        }

        .finalizar-container {
            display: flex;
            justify-content: space-between; /* Distribui os botões à esquerda e direita */
            margin-top: 20px;
        }

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
                            <td><img class="imagem" src="uploads/<?php echo htmlspecialchars($item["imagem"]); ?>" alt="<?php echo htmlspecialchars($item["nome"]); ?>"></td>
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

                <div class="finalizar-container">
                    <button type="submit" name="update_cart" class="botaoatualizar">Atualizar Carrinho</button>
                    <a href="finalizar.php" class="button">Finalizar Compra</a>
                </div>
            </form>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>

        <br><br>
        <a href="index.php" class="botaovoltar">Continuar Comprando</a>
    </div>

    <?php include("includes/footer.php"); ?>
</body>
</html>
