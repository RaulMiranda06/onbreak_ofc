<?php
session_start();
if (!isset($_SESSION['pedido_id'])) {
    header("Location: index.php");
    exit;
}

$pedido_id = $_SESSION['pedido_id'];
unset($_SESSION['pedido_id']); // Limpa o ID do pedido da sessão

include("includes/conexao.php");
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            color: #333;
            margin-top: 50px;
        }

        p {
            font-size: 1.2em;
            color: #333;
            margin-top: 20px;
        }

        a {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        .container {
            padding: 20px;
        }

        .status {
            background-color: #e0f7fa;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            display: inline-block;
        }

        .order-id {
            font-weight: bold;
            color: #007bff;
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Obrigado pelo seu pedido!</h1>
        <p>Seu pedido <span class="order-id">#<?php echo $pedido['id']; ?></span> foi confirmado e está sendo processado.</p>
        <div class="status">
            <p>Status: <?php echo $pedido['status']; ?></p>
        </div>
        <a href="index.php">Voltar para o inicio</a>
    </div>
</body>
</html>
