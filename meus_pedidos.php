<?php
session_start();
include("includes/conexao.php");

// Verifica se há pedidos para o usuário
$stmt = $pdo->prepare("SELECT * FROM pedidos ORDER BY data_pedido DESC");
$stmt->execute();
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
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
        <h1>Meus Pedidos</h1>

        <?php if (empty($pedidos)): ?>
            <p>Você ainda não fez nenhum pedido. <a href="index.php">Clique aqui</a> para fazer seu primeiro pedido.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID do Pedido</th>
                        <th>Data do Pedido</th>
                        <th>Status</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?php echo $pedido['id']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                            <td class="status <?php echo strtolower($pedido['status']); ?>">
                                <?php echo ucfirst($pedido['status']); ?>
                            </td>
                            <td><a href="detalhes_pedido.php?id=<?php echo $pedido['id']; ?>">Ver Detalhes</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <a href="index.php">
            <button>Voltar para o inicio </button>
        </a>
    </div>
</body>
</html>
