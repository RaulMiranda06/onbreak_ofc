<?php
session_start();
include("includes/conexao.php");

// Verifica se o carrinho está vazio
if (empty($_SESSION['cart'])) {
    header("Location: carrinho.php");
    exit;
}

// Verifica se os dados do cliente foram passados corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização das entradas
    $nome_cliente = isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : null;
    $telefone = isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : null;
    $horario = isset($_POST['horario']) ? $_POST['horario'] : null;

    // Verifique se todos os campos necessários estão preenchidos
    if (empty($nome_cliente) || empty($telefone) || empty($horario)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Calcula o total
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }

    // Insere o pedido na tabela pedidos
    try {
        $stmt = $pdo->prepare("INSERT INTO pedidos (nome_cliente, telefone, total, status, data_pedido, horario) 
                               VALUES (:nome_cliente, :telefone, :total, 'Aguardando Pagamento', NOW(), :horario)");
        $stmt->bindParam(':nome_cliente', $nome_cliente);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':horario', $horario);
        $stmt->execute();
        $pedido_id = $pdo->lastInsertId();

        // Salva os itens do pedido na tabela pedido_itens
        foreach ($_SESSION['cart'] as $item) {
            $subtotal = $item['preco'] * $item['quantidade'];
            $stmt = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, nome_lanche, preco, quantidade, subtotal) 
                                   VALUES (:pedido_id, :nome_lanche, :preco, :quantidade, :subtotal)");
            $stmt->bindParam(':pedido_id', $pedido_id);
            $stmt->bindParam(':nome_lanche', $item['nome']);
            $stmt->bindParam(':preco', $item['preco']);
            $stmt->bindParam(':quantidade', $item['quantidade']);
            $stmt->bindParam(':subtotal', $subtotal);
            $stmt->execute();
        }

        // Salva o ID do pedido e redireciona para a página de pagamento
        $_SESSION['pedido_id'] = $pedido_id;
        header("Location: pagamentos.php"); // Redireciona para a página de pagamento
        exit;

    } catch (PDOException $e) {
        echo "Erro ao processar o pedido: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
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

        .imagem {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .total-price {
            font-size: 1.8em;
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
            font-size: 1.1em;
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

        form input[type="time"] {
            width: 180px;
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
            max-width: 200px;
            margin: 20px auto;
            transition: background-color 0.3s ease;
        }

        button.button:hover {
            background-color: #0056b3;
        }

        button.button:focus {
            outline: none;
        }

        @media (min-width: 768px) {
            form input,
            form select {
                width: 48%;
            }

            button.button {
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
        <h1>Detalhes do Pedido</h1>

        <h2>Itens no Carrinho:</h2>
        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
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
                    <td><?php echo $item["quantidade"]; ?></td>
                    <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-price">
            Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
        </div>

        <h2>Informações do Cliente:</h2>
        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" required>

            <label for="horario">Horário de Retirada:</label>
            <input type="time" name="horario" id="horario" required>

            <button type="submit" class="button">Confirmar Pedido</button>
        </form>
    </div>
    <br><br><br>
    <?php include("includes/footer.php"); ?>
</body>
</html>
