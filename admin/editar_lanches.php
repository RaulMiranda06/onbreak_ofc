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
        /* Layout geral */
        body {
            font-family: Arial, sans-serif;
            background-color: #fffae6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #ff6600;
            text-align: center;
        }

        /* Estilos para o formulário */
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .btn-submit {
            background-color: #ff6600;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-submit:hover {
            background-color: #e65c00;
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
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($lanche['nome']); ?>" required>

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="4" required><?php echo htmlspecialchars($lanche['descricao']); ?></textarea>

            <label for="preco">Preço</label>
            <input type="number" name="preco" id="preco" value="<?php echo htmlspecialchars($lanche['preco']); ?>" step="0.01" required>

            <label for="estoque">Estoque</label>
            <input type="number" name="estoque" id="estoque" value="<?php echo htmlspecialchars($lanche['estoque']); ?>" required>

            <label for="imagem">Imagem (Deixe em branco para manter a imagem atual)</label>
            <input type="file" name="imagem" id="imagem">

            <button type="submit" class="btn-submit">Salvar Alterações</button>
        </form>
        
        <br>
        <!-- Botão de Voltar -->
        <a href="listar_lanches.php" class="btn-back">Voltar para a lista de lanches</a>
    </div>
</body>
</html>
