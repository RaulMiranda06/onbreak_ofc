<?php
session_start();
include("../includes/conexao.php");
include("header.php");

// Verifica se o id do pedido foi passado pela URL
if (!isset($_GET['id'])) {
    header("Location: admin_pedidos.php");  // Redireciona se o id não for encontrado
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

// Variável para armazenar a mensagem de status
$status_message = '';

// Atualiza o status do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_status = $_POST['status'];

    // Atualiza o status no banco de dados
    $updateStmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $updateStmt->execute([$novo_status, $pedido_id]);

    // Mensagem de sucesso
    $status_message = "Status do pedido #{$pedido_id} atualizado para '{$novo_status}'.";
    // Recarrega os detalhes do pedido após atualização
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Status do Pedido #<?php echo $pedido['id']; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 2.2em;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #555;
        }

        select {
            padding: 12px 20px;
            font-size: 1.1em;
            margin-top: 20px;
            width: 220px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        select:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 12px 30px;
            font-size: 1.2em;
            margin-top: 20px;
            width: 220px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: inline-block;
            text-align: center;
            margin-top: 30px;
        }

        .back-btn button {
            background-color: #28a745;
            width: 220px;
        }

        .back-btn button:hover {
            background-color: #218838;
        }

        .status-message {
            color: #28a745;
            font-size: 1.1em;
            margin-top: 20px;
            text-align: center;
        }

        .status-message.error {
            color: #e74c3c;
        }

        /* Estilo para o status "finalizado" */
        .status.finalizado {
            color: #6c757d;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <br><br><br>
    <div class="container">
        <h1>Atualizar Status do Pedido #<?php echo $pedido['id']; ?></h1>

        <!-- Exibe a mensagem de status -->
        <?php if ($status_message): ?>
            <div class="status-message"><?php echo $status_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="status">Selecione o novo status:</label>
            <select name="status" id="status">
                <option value="pendente" <?php echo ($pedido['status'] === 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                <option value="pago" <?php echo ($pedido['status'] === 'pago') ? 'selected' : ''; ?>>Pago</option>
                <option value="cancelado" <?php echo ($pedido['status'] === 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                <option value="finalizado" <?php echo ($pedido['status'] === 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
            </select>
            <button type="submit">Atualizar Status</button>
        </form>

        <div class="back-btn">
            <a href="admin_pedidos.php">
                <button>Voltar para a Lista de Pedidos</button>
            </a>
        </div>
    </div>
</body>
</html>
