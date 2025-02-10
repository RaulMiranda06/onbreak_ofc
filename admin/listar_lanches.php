<?php
include("conexao.php"); // Inclui a conexão com o banco de dados

// Função para exibir mensagens de erro ou sucesso
function exibirMensagem($mensagem, $tipo = 'error') {
    return "<div class='alert-message-$tipo'>$mensagem</div>";
}

// Remover Lanche
if (isset($_GET['action']) && $_GET['action'] == 'remover' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar o lanche no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM lanches WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $lanche = $stmt->fetch();

    if ($lanche) {
        // Deletar o lanche
        $stmt = $pdo->prepare("DELETE FROM lanches WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Deletar a imagem da pasta uploads
            unlink("../uploads/" . $lanche['imagem']);
            header('Location: listar_lanches.php');
            exit;
        }
    }
}

// Consultar todos os lanches cadastrados
$stmt = $pdo->prepare("SELECT * FROM lanches");
$stmt->execute();
$lanches = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Lanches</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #ff7043;
            font-size: 36px;
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
            border: 1px solid #ddd;
            color: #333;
        }

        th {
            background-color: #ff7043;
            color: white;
        }

        td img {
            width: 80px;
            border-radius: 8px;
        }

        .action-buttons a {
            display: inline-block;
            padding: 8px 16px;
            margin-right: 10px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .action-buttons a:hover {
            background-color: #1976D2;
        }

        .action-buttons .delete {
            background-color: #e53935;
        }

        .action-buttons .delete:hover {
            background-color: #d32f2f;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff7043;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #f4511e;
            transform: scale(1.05);
        }

        .alert-message-success,
        .alert-message-error {
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-message-success {
            background-color: #4CAF50;
            color: white;
        }

        .alert-message-error {
            background-color: #f44336;
            color: white;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 28px;
            }

            table {
                font-size: 14px;
            }

            td img {
                width: 60px;
            }

            .action-buttons a {
                padding: 6px 12px;
                font-size: 14px;
            }

            .button {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Listar Lanches Cadastrados</h2>

        <!-- Exibir alertas de sucesso ou erro -->
        <?php if (isset($mensagem)): ?>
            <?php echo exibirMensagem($mensagem, 'success'); ?>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lanches as $lanche): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lanche['nome']); ?></td>
                        <td><?php echo htmlspecialchars($lanche['descricao']); ?></td>
                        <td>R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></td>
                        <td><img src="../uploads/<?php echo htmlspecialchars($lanche['imagem']); ?>" alt="Imagem do Lanche"></td>
                        <td class="action-buttons">
                            <a href="editar_lanches.php?action=editar&id=<?php echo $lanche['id']; ?>">Editar</a>
                            <a href="?action=remover&id=<?php echo $lanche['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir este lanche?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="button">Voltar ao Início</a>
    </div>
</body>
</html>
