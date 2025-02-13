<?php
session_start();
include("includes/conexao.php");

// Verifica se o pedido foi criado e se o ID do pedido está presente na sessão
if (!isset($_SESSION['pedido_id'])) {
    header("Location: index.php");  // Redireciona para a loja caso não haja pedido
    exit;
}

$pedido_id = $_SESSION['pedido_id'];

// Obtém os detalhes do pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Atualiza o status do pedido para "Aguardando Pagamento"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pagamento = $_POST['pagamento']; // Método de pagamento escolhido

    try {
        // Atualiza o status do pedido e registra o método de pagamento
        $stmt = $pdo->prepare("UPDATE pedidos SET status = 'Aguardando Pagamento', pagamento = ? WHERE id = ?");
        $stmt->execute([$pagamento, $pedido_id]);

        // Redireciona para a página de confirmação ou agradecimento
        header("Location: obrigado.php");
        exit;

    } catch (PDOException $e) {
        echo "Erro ao processar pedido: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo do Pedido</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fb;
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 1.6em;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #fafafa;
            font-size: 1em;
        }

        .total-price {
            font-size: 2em;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
            text-align: right;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        form label {
            font-size: 1.2em;
            color: #333;
            width: 100%;
        }

        form input,
        form select {
            padding: 12px;
            font-size: 1.1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        button.button {
            background-color: #007bff;
            color: white;
            padding: 14px 30px;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            max-width: 250px;
            margin: 20px auto;
            transition: background-color 0.3s ease;
        }

        button.button:hover {
            background-color: #0056b3;
        }

        button.button:focus {
            outline: none;
        }

        button.back-button {
            background-color: #f1f1f1;
            color: #007bff;
            padding: 12px 30px;
            font-size: 1.1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            max-width: 250px;
            margin: 20px auto;
            transition: background-color 0.3s ease;
        }

        button.back-button:hover {
            background-color: #e1e1e1;
        }

        button.back-button:focus {
            outline: none;
        }

        @media (min-width: 768px) {
            form input,
            form select {
                width: 48%;
            }

            button.button, button.back-button {
                width: auto;
            }
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2em;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <br>
    <div class="container">
        <h1>Resumo do Pedido</h1>

        <h2>Resumo dos Itens</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM pedido_itens WHERE pedido_id = ?");
                $stmt->execute([$pedido_id]);
                $itens = $stmt->fetchAll();

                $total = 0;
                foreach ($itens as $item):
                    $subtotal = $item['preco'] * $item['quantidade'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nome_lanche']); ?></td>
                        <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-price">
            Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
        </div>

        <h2>Escolha a Forma de Pagamento:</h2>
        <form method="post">
            <label for="pagamento">Método de Pagamento:</label>
            <select name="pagamento" id="pagamento" required>
                <option value="Pix">Pix</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
                <option value="Dinheiro">Dinheiro (Pagar na Retirada)</option>
            </select>

            <button type="submit" class="button">Confirmar Pedido</button>
        </form>

        <!-- Botão para voltar ao checkout -->
        <a href="checkout.php">
            <button class="back-button">Voltar ao Checkout</button>
        </a>
    </div>
</body>
</html>
