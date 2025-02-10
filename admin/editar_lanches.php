<?php
include("conexao.php"); // Inclui a conexão com o banco de dados

// Função para exibir mensagens de erro ou sucesso
function exibirMensagem($mensagem, $tipo = 'error') {
    return "<div class='alert-message-$tipo'>$mensagem</div>";
}

// Ação para editar o lanche
if (isset($_GET['action']) && $_GET['action'] == 'editar' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = htmlspecialchars(trim($_POST['nome']));
        $descricao = htmlspecialchars(trim($_POST['descricao']));
        $preco = $_POST['preco'];

        // Verificar se o preço é válido
        if (!is_numeric($preco) || $preco <= 0) {
            $mensagem = "Preço inválido!";
        } else {
            // Atualizar os dados no banco de dados
            $stmt = $pdo->prepare("UPDATE lanches SET nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $mensagem = "Lanche atualizado com sucesso!";
                header('Location: listar_lanches.php');
                exit;
            } else {
                $mensagem = "Erro ao atualizar o lanche!";
            }
        }
    }

    // Buscar os dados do lanche a ser editado
    $stmt = $pdo->prepare("SELECT * FROM lanches WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $lanche = $stmt->fetch();

    if (!$lanche) {
        header('Location: listar_lanches.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lanche</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            color: #ff7043;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .input-group input, .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        .input-group textarea {
            resize: vertical;
            height: 120px;
        }

        .button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #ff7043;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
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

        a.button {
            width: auto;
            margin-top: 10px;
            text-align: center;
            display: inline-block;
            background-color: #2196F3;
        }

        a.button:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Lanche</h2>

        <!-- Exibir alertas de sucesso ou erro -->
        <?php if (isset($mensagem)): ?>
            <?php echo exibirMensagem($mensagem, 'success'); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label for="nome">Nome do Lanche</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($lanche['nome']); ?>" required>
            </div>

            <div class="input-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($lanche['descricao']); ?></textarea>
            </div>

            <div class="input-group">
                <label for="preco">Preço</label>
                <input type="text" name="preco" id="preco" value="<?php echo htmlspecialchars($lanche['preco']); ?>" required>
            </div>

            <button class="button" type="submit">Atualizar Lanche</button>
        </form>

        <a href="listar_lanches.php" class="button">Voltar para a Lista</a>
    </div>
</body>
</html>
