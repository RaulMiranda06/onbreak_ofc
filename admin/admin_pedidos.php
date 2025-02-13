<?php
session_start();
include("../includes/conexao.php");
include("header.php");
// Obtém todos os pedidos do banco de dados
$stmt = $pdo->prepare("SELECT * FROM pedidos ORDER BY data_pedido DESC");
$stmt->execute();
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Pedidos</title>
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
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
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
            text-transform: uppercase;
        }

        td {
            background-color: #fafafa;
            font-size: 1em;
            color: #555;
        }

        .status {
            color: #333;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pendente {
            color: #ff9800;
        }

        .status.pago {
            color: #4caf50;
        }

        .status.cancelado {
            color: #f44336;
        }

        .actions a {
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: color 0.3s ease;
            padding: 6px 10px;
            border-radius: 5px;
            margin-right: 10px;
        }

        .actions a.ver-detalhes {
            background-color: #007bff;
            color: white;
        }

        .actions a.atualizar-status {
            background-color: #ff9800;
            color: white;
        }

        .actions a.cancelar-pedido {
            background-color: #f44336;
            color: white;
        }

        .actions a:hover {
            opacity: 0.8;
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

        .empty-message {
            text-align: center;
            font-size: 1.2em;
            color: #888;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            width: 300px;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }

            table {
                font-size: 0.9em;
            }

            .actions {
                display: block;
                text-align: center;
            }

            .actions a {
                margin: 5px 0;
                width: 100%;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <br><br><br><br>
    <div class="container">
        <h1>Administração de Pedidos</h1>

        <?php if (empty($pedidos)): ?>
            <p class="empty-message">Não há pedidos registrados no momento.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID do Pedido</th>
                        <th>Data do Pedido</th>
                        <th>Status</th>
                        <th>Ações</th>
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
                            <td class="actions">
                                <a href="detalhes_pedido.php?id=<?php echo $pedido['id']; ?>" class="ver-detalhes">Ver Detalhes</a>
                                <a href="atualizar_status.php?id=<?php echo $pedido['id']; ?>" class="atualizar-status">Atualizar Status</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <a href="index.php">
            <button>Voltar para a Loja</button>
        </a>
    </div>

    <?php include("footer.php"); ?>                

</body>
</html>
