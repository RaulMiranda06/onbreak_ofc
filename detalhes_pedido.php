<?php
session_start();
include("includes/conexao.php");

// Verifica se o parâmetro 'id' foi passado pela URL
if (!isset($_GET['id'])) {
    header("Location: meus_pedidos.php");  // Redireciona para a página de pedidos se o id não for passado
    exit;
}

$pedido_id = $_GET['id'];

// Obtém os detalhes do pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();

// Verifica se o pedido existe
if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Obtém os itens do pedido
$stmt = $pdo->prepare("SELECT * FROM pedido_itens WHERE pedido_id = ?");
$stmt->execute([$pedido_id]);
$itens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido #<?php echo $pedido['id']; ?></title>
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

        .status {
            color: #333;
            font-weight: bold;
        }

        .status.pendente {
            color: orange;
        }

        .status.pago {
            color: green;
        }

        .status.cancelado {
            color: red;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #007bff;
            color: white;
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

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalhes do Pedido #<?php echo $pedido['id']; ?></h1>

        <p><strong>Data do Pedido:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
        <p><strong>Status do Pedido:</strong> <span class="status <?php echo strtolower($pedido['status']); ?>"><?php echo ucfirst($pedido['status']); ?></span></p>

        <h2>Itens do Pedido</h2>
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
            <strong>Total:</strong> R$ <?php echo number_format($total, 2, ',', '.'); ?>
        </div>

        <a href="meus_pedidos.php">
            <button>Voltar para Meus Pedidos</button>
        </a>
    </div>
</body>
</html>
