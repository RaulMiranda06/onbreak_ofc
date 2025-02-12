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
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            overflow: auto;
        }

        h2 {
            text-align: center;
            color: #f4511e;
            margin-bottom: 20px;
            font-size: 26px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        td img {
            max-width: 80px;
            height: auto;
        }

        a {
            text-decoration: none;
            font-weight: bold;
        }

        .btn-back {
            display: inline-block;
            width: 300px;
            padding: 15px;
            text-align: center;
            background-color: #1E88E5;
            color: white;
            font-size: 18px;
            border-radius: 6px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-back:hover {
            background-color: #1565C0;
            transform: scale(1.05);
        }

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

        /* Responsivo para telas menores */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                max-width: 100%;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            .btn-back {
                padding: 12px;
                font-size: 16px;
            }
        }

        /* Responsivo para dispositivos móveis (menor que 480px) */
        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }

            .btn-back {
                padding: 10px;
                font-size: 14px;
            }
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
                            <a href="editar_lanche.php?id=<?php echo $lanche['id']; ?>" style="color: #2d87f0;">Editar</a> |
                            <a href="?excluir=<?php echo $lanche['id']; ?>" style="color: #f44336;" onclick="return confirm('Tem certeza que deseja excluir este lanche?')">Excluir</a>
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
