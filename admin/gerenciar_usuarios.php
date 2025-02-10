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
        /* Estilos para a tabela de usuários */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f4511e;
            color: white;
        }
        .button {
            padding: 8px 15px;
            border: none;
            background-color: #f4511e;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #e64a19;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #4CAF50;
            color: white;
        }
        .alert-error {
            background-color: #f44336;
            color: white;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0b7dda;
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
