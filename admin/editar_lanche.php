<?php
include("conexao.php"); // Inclui a conexão com o banco de dados

// Função para exibir mensagens de erro ou sucesso
function exibirMensagem($mensagem, $tipo = 'error') {
    return "<div class='alert-message-$tipo'>$mensagem</div>";
}

// Verifique se o parâmetro 'id' está presente na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar o lanche a ser editado
    $stmt = $pdo->prepare("SELECT * FROM lanches WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $lanche = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lanche) {
        $mensagem = exibirMensagem('Lanche não encontrado.', 'error');
    }
} else {
    $mensagem = exibirMensagem('ID do lanche não informado.', 'error');
}

// Atualizar o lanche
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    // Verificar se uma nova imagem foi carregada
    if ($_FILES['imagem']['name']) {
        $imagem = $_FILES['imagem'];
        $imagem_nome = time() . '-' . basename($imagem['name']);
        $imagem_destino = '../uploads/' . $imagem_nome;

        if (move_uploaded_file($imagem['tmp_name'], $imagem_destino)) {
            // Atualiza a imagem
            $stmt = $pdo->prepare("UPDATE lanches SET nome = :nome, descricao = :descricao, preco = :preco, estoque = :estoque, imagem = :imagem WHERE id = :id");
            $stmt->bindParam(':imagem', $imagem_nome);
        } else {
            $mensagem = exibirMensagem('Erro ao fazer upload da imagem.', 'error');
        }
    } else {
        // Não há nova imagem, então mantém a imagem antiga
        $stmt = $pdo->prepare("UPDATE lanches SET nome = :nome, descricao = :descricao, preco = :preco, estoque = :estoque WHERE id = :id");
    }

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':estoque', $estoque);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $mensagem = exibirMensagem('Lanche atualizado com sucesso!', 'success');
    } else {
        $mensagem = exibirMensagem('Falha ao atualizar o lanche. Tente novamente.', 'error');
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
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #f4511e;
            margin-bottom: 20px;
            font-size: 26px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .input-group textarea {
            resize: vertical;
        }

        .btn-submit {
            width: 390px;
            padding: 15px;
            background-color: #f4511e;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-submit:hover {
            background-color: #e64a19;
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

        .btn-back {
            display: inline-block;
            width: 360px;
            padding: 15px;
            text-align: center;
            background-color: #1E88E5;
            color: white;
            font-size: 18px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-back:hover {
            background-color: #1565C0;
            transform: scale(1.05);
        }

        /* Responsivo para telas menores */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                max-width: 100%;
            }

            h2 {
                font-size: 22px;
            }

            .input-group input,
            .input-group textarea {
                font-size: 14px;
                padding: 10px;
            }

            .btn-submit,
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

            .btn-submit,
            .btn-back {
                padding: 10px;
                font-size: 14px;
            }

            h2 {
                font-size: 20px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Lanche</h2>

        <!-- Exibir mensagens de erro ou sucesso -->
        <?php if (isset($mensagem)): ?>
            <?php echo $mensagem; ?>
        <?php endif; ?>

        <!-- Formulário de Edição -->
        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($lanche['nome']); ?>" required>
            </div>

            <div class="input-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" required><?php echo htmlspecialchars($lanche['descricao']); ?></textarea>
            </div>

            <div class="input-group">
                <label for="preco">Preço</label>
                <input type="number" name="preco" id="preco" value="<?php echo htmlspecialchars($lanche['preco']); ?>" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="estoque">Estoque</label>
                <input type="number" name="estoque" id="estoque" value="<?php echo htmlspecialchars($lanche['estoque']); ?>" required>
            </div>

            <div class="input-group">
                <label for="imagem">Imagem (Deixe em branco para manter a imagem atual)</label>
                <input type="file" name="imagem" id="imagem">
            </div>

            <button type="submit" class="btn-submit">Salvar Alterações</button>
        </form>

        <br>
        <!-- Botão de Voltar -->
        <a href="listar_lanches.php" class="btn-back">Voltar para a lista de lanches</a>
    </div>
</body>
</html>
