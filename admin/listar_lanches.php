<?php
include("conexao.php"); // Inclui a conexão com o banco de dados

// Função para exibir mensagens de erro ou sucesso
function exibirMensagem($mensagem, $tipo = 'error') {
    return "<div class='alert-message-$tipo'>$mensagem</div>";
}

// Verifique se o parâmetro 'excluir' está presente na URL
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];

    // Preparar a consulta para excluir o lanche
    $stmt = $pdo->prepare("DELETE FROM lanches WHERE id = :id");
    $stmt->bindParam(':id', $id);

    // Executar a consulta e verificar sucesso
    if ($stmt->execute()) {
        $mensagem = exibirMensagem('Lanche excluído com sucesso!', 'success');
    } else {
        $mensagem = exibirMensagem('Falha ao excluir o lanche. Por favor, tente novamente.', 'error');
    }
}

// Buscar todos os lanches cadastrados
$stmt = $pdo->query("SELECT * FROM lanches");
$lanches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Lanches</title>
    <style>
        /* Layout geral */
        body {
            font-family: Arial, sans-serif;
            background-color: #fffae6; /* Cor de fundo suave */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #ff6600; /* Cor laranja */
            text-align: center;
        }

        /* Estilos para a tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #ff6600;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilo para os alertas */
        .alert-message-success {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .alert-message-error {
            padding: 15px;
            background-color: #f44336;
            color: white;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Estilos do botão "Voltar" */
        .btn-back {
            display: inline-block;
            padding: 12px 24px;
            background-color: #1E88E5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .btn-back:hover {
            background-color:rgb(0, 60, 255);
        }

        /* Estilo das imagens */
        img {
            max-width: 50px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Lanches</h2>

        <!-- Exibir alertas de sucesso ou erro -->
        <?php if (isset($mensagem)): ?>
            <?php echo $mensagem; ?>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lanches as $lanche): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lanche['nome']); ?></td>
                        <td><?php echo htmlspecialchars($lanche['descricao']); ?></td>
                        <td><?php echo number_format($lanche['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $lanche['estoque']; ?></td>
                        <td><img src="../uploads/<?php echo htmlspecialchars($lanche['imagem']); ?>" alt="Imagem do Lanche"></td>
                        <td>
                            <a href="editar_lanche.php?id=<?php echo $lanche['id']; ?>">Editar</a> |
                            <a href="?excluir=<?php echo $lanche['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este lanche?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botão de Voltar -->
        <a href="index.php" class="btn-back">Voltar para o Início</a>
    </div>
</body>
</html>
