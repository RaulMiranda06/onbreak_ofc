<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['permissao'] !== 'admin') {
    header('Location: login_admin.php');
    exit();
}

include('../includes/conexao.php'); // Conexão com o banco de dados

// Ação de exclusão de usuário
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Deletar o usuário
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Usuário excluído com sucesso.";
    } else {
        $_SESSION['error'] = "Erro ao excluir o usuário.";
    }
    header('Location: gerenciar_usuarios.php');
    exit();
}

// Ação de editar usuário
if (isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $permissao = $_POST['permissao'];

    // Validação de campos
    if (empty($email) || empty($permissao)) {
        $_SESSION['error'] = "Por favor, preencha todos os campos.";
    } else {
        // Atualizar usuário no banco de dados
        $stmt = $pdo->prepare("UPDATE usuarios SET email = :email, permissao = :permissao WHERE id = :id");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':permissao', $permissao);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Usuário atualizado com sucesso.";
        } else {
            $_SESSION['error'] = "Erro ao atualizar o usuário.";
        }
    }
    header('Location: gerenciar_usuarios.php');
    exit();
}

// Carregar todos os usuários
$usuarios = [];
try {
    $stmt = $pdo->prepare("SELECT id, email, permissao, criado_em FROM usuarios");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Erro ao buscar usuários: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <style>
        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Título */
        h2 {
            color: #f4511e;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Botão de Voltar */
        .button {
            background-color: #1e88e5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .button:hover {
            background-color: #1565c0;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4511e;
            color: white;
        }

        /* Alertas */
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
    </style>
</head>
<body>

    <div class="container">
        <h2>Gerenciar Usuários</h2>

        <!-- Botão para voltar ao dashboard -->
        <a href="dashboard_admin.php">
            <button class="button">Voltar ao inicio</button>
        </a>

        <!-- Exibição de mensagens de erro ou sucesso -->
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
            <thead>
                <tr>
                    <th>ID</th>
                    <th>E-mail</th>
                    <th>Permissão</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['permissao']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['criado_em']); ?></td>
                            <td>
                                <!-- Botões de Editar e Excluir -->
                                <button class="button" onclick="document.getElementById('editModal<?php echo $usuario['id']; ?>').style.display='block'">Editar</button>
                                <a href="?action=delete&id=<?php echo $usuario['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                    <button class="button">Excluir</button>
                                </a>
                            </td>
                        </tr>

    
                        <!-- Modal de Edição -->
                        <div id="editModal<?php echo $usuario['id']; ?>" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background-color: rgba(0,0,0,0.5); padding: 50px;">
                            <div style="background-color: white; padding: 20px; border-radius: 6px; max-width: 400px; margin: auto;">
                                <h3>Editar Usuário</h3>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <label for="email">E-mail</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required><br><br>

                                    <label for="permissao">Permissão</label>
                                    <select name="permissao" required>
                                        <option value="usuario" <?php echo $usuario['permissao'] == 'usuario' ? 'selected' : ''; ?>>Usuário</option>
                                        <option value="admin" <?php echo $usuario['permissao'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select><br><br>

                                    <button class="button" type="submit">Atualizar</button>
                                    <input type="hidden" name="action" value="edit">
                                </form>
                                <button class="button" onclick="document.getElementById('editModal<?php echo $usuario['id']; ?>').style.display='none'">Fechar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</body>
</html>

