<?php
session_start();
include('../includes/conexao.php');

// Verifica se há um ID de usuário a ser excluído
if (isset($_GET['excluir_id'])) {
    $excluir_id = $_GET['excluir_id'];

    // Deleta o usuário do banco de dados
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $excluir_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Usuário excluído com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao excluir o usuário.";
    }

    // Redireciona de volta para a página de gerenciamento
    header("Location: gerenciar_usuarios.php");
    exit();
}

// Busca todos os usuários
$stmt = $pdo->query("SELECT * FROM clientes");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4511e;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .button {
            padding: 8px 15px;
            background-color: #1E88E5;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            margin: 5px;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #1565C0;
            transform: scale(1.05);
        }

        .back-button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #1E88E5;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 18px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background-color: #1565C0;
            transform: scale(1.05);
        }

        .alert {
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            font-size: 16px;
            text-align: center;
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
        }

        .alert-error {
            background-color: #f44336;
            color: white;
        }

        /* Responsivo para telas menores */
        @media (max-width: 768px) {
            table {
                width: 100%;
                margin: 10px;
            }

            .button, .back-button {
                font-size: 14px;
                padding: 10px;
            }

            h2 {
                font-size: 24px;
            }

            th, td {
                font-size: 14px;
            }
        }

        /* Responsivo para dispositivos móveis (menor que 480px) */
        @media (max-width: 480px) {
            .button, .back-button {
                font-size: 12px;
                padding: 8px;
            }

            h2 {
                font-size: 20px;
            }

            table {
                margin: 10px;
            }

            th, td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<h2>Gerenciar Usuários</h2>

<?php
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-error'>".$_SESSION['error']."</div>";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}
?>

<table>
    <tr>
        <th>ID</th>
        <th>E-mail</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
            <td>
                <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="button">Editar</a>
                <a href="gerenciar_usuarios.php?excluir_id=<?php echo $usuario['id']; ?>" class="button" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Botão de Voltar -->
<a href="index.php" class="back-button">Voltar para a Página Inicial</a>

</body>
</html>
