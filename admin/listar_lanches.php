<?php
include("../includes/conexao.php"); // Inclui a conexão com o banco de dados

// Variáveis para mensagens de alerta
$alertaMensagem = '';
$alertaTipo = ''; // Tipo de alerta ('sucesso', 'erro', etc.)

// Consultar todos os lanches cadastrados
$stmt = $pdo->prepare("SELECT * FROM lanches");
$stmt->execute();
$lanches = $stmt->fetchAll();

// Verifica se o formulário de edição foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_lanche'])) {
    // Pegando os dados do formulário
    $id = $_POST['id'];
    $nome = htmlspecialchars($_POST['nome']);  // Evita XSS
    $descricao = htmlspecialchars($_POST['descricao']);
    $preco = number_format(floatval($_POST['preco']), 2, '.', ''); // Formata o preço

    // Se foi enviado uma nova imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem = $_FILES['imagem'];

        // Verifica o erro no upload do arquivo
        if ($imagem['error']) {
            $alertaMensagem = 'Falha ao enviar o arquivo. Erro: ' . $imagem['error'];
            $alertaTipo = 'erro';
        }

        // Limita o tamanho do arquivo (máximo de 2MB)
        if ($imagem['size'] > 2097152) {
            $alertaMensagem = 'Arquivo muito grande! O tamanho máximo é 2MB.';
            $alertaTipo = 'erro';
        }

        // Pasta onde o arquivo será armazenado
        $pasta = "uploads/";

        // Obtém a extensão do arquivo original
        $nomeImagem = $imagem['name'];
        $extensao = strtolower(pathinfo($nomeImagem, PATHINFO_EXTENSION));

        // Verifica se a extensão é permitida
        if ($extensao != "jpg" && $extensao != "png") {
            $alertaMensagem = 'Tipo de arquivo não aceito. Apenas imagens JPG e PNG são permitidas.';
            $alertaTipo = 'erro';
        }

        // Verifica o tipo MIME da imagem para garantir que é uma imagem
        $tipoMime = mime_content_type($imagem['tmp_name']);
        if ($tipoMime !== 'image/jpeg' && $tipoMime !== 'image/png') {
            $alertaMensagem = 'Tipo de arquivo não aceito. Apenas imagens JPG e PNG são permitidas.';
            $alertaTipo = 'erro';
        }

        // Gera um nome único para a imagem
        $novoNomeImagem = uniqid();
        $path = $pasta . $novoNomeImagem . '.' . $extensao;

        // Move o arquivo para o destino
        if (!move_uploaded_file($imagem['tmp_name'], $path)) {
            $alertaMensagem = 'Falha ao mover o arquivo de imagem.';
            $alertaTipo = 'erro';
        }

        // Atualiza a variável de imagem com o caminho
        $imagemPath = $path;
    } else {
        // Se não foi enviada uma nova imagem, mantém a imagem anterior
        $imagemPath = null;
    }

    // Se uma nova imagem foi enviada, atualiza o caminho da imagem
    if ($imagemPath) {
        $stmt = $pdo->prepare("UPDATE lanches SET nome = :nome, descricao = :descricao, preco = :preco, imagem = :imagem WHERE id = :id");
        $stmt->bindParam(':imagem', $imagemPath);
    } else {
        // Caso não tenha sido enviada uma imagem, não atualiza a imagem
        $stmt = $pdo->prepare("UPDATE lanches SET nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id");
    }

    // Atualiza os dados do lanche
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $alertaMensagem = 'Lanche atualizado com sucesso!';
        $alertaTipo = 'sucesso';
    } else {
        $alertaMensagem = 'Falha ao atualizar o lanche. Tente novamente.';
        $alertaTipo = 'erro';
    }
}

// Verifica se o ID para remoção foi enviado
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    $stmt = $pdo->prepare("DELETE FROM lanches WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $alertaMensagem = 'Lanche removido com sucesso!';
        $alertaTipo = 'sucesso';
    } else {
        $alertaMensagem = 'Falha ao remover o lanche. Tente novamente.';
        $alertaTipo = 'erro';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar e Editar Lanches</title>
    <style>
        /* Estilos básicos para a página */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            width: 80%;
            max-width: 1000px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #f4511e;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 10px;
        }

        th {
            background-color: #f4511e;
            color: white;
        }

        .button {
            padding: 10px 20px;
            background-color: #f4511e;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            text-decoration: none;
        }

        .button:hover {
            background-color: #e64a19;
        }

        /* Estilo para o alerta */
        .alerta {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
            display: none; /* Inicialmente escondido */
        }

        .alerta.sucesso {
            background-color: #4CAF50;
            color: white;
        }

        .alerta.erro {
            background-color: #f44336;
            color: white;
        }

        /* Visibilidade do alerta */
        .alerta.show {
            display: block;
        }

        .input-group input, .input-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .input-group textarea {
            resize: vertical;
        }

        .back-button {
            display: inline-block;
            width: 100%;
            padding: 15px;
            text-align: center;
            background-color: #1E88E5;
            color: white;
            font-size: 18px;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px;
            box-sizing: border-box;
        }

        .back-button:hover {
            background-color: #1565C0;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Listar e Editar Lanches</h2>

        <!-- Alerta em CSS -->
        <?php if ($alertaMensagem): ?>
            <div class="alerta <?php echo $alertaTipo; ?> show">
                <?php echo $alertaMensagem; ?>
            </div>
        <?php endif; ?>

        <table>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($lanches as $lanche): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lanche['nome']); ?></td>
                    <td><?php echo htmlspecialchars($lanche['descricao']); ?></td>
                    <td>R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></td>
                    <td><img src="<?php echo $lanche['imagem']; ?>" alt="Imagem do Lanche" width="50"></td>
                    <td>
                        <button class="button" onclick="editarLanche(<?php echo $lanche['id']; ?>, '<?php echo addslashes($lanche['nome']); ?>', '<?php echo addslashes($lanche['descricao']); ?>', '<?php echo $lanche['preco']; ?>', '<?php echo $lanche['imagem']; ?>')">Editar</button>
                        <a href="?remover=<?php echo $lanche['id']; ?>" class="button">Remover</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Formulário de Edição -->
        <div id="formulario-edicao" style="display:none;">
            <h3>Editar Lanche</h3>
            <form method="POST" enctype="multipart/form-data" action="">
                <input type="hidden" name="id" id="id">
                <div class="input-group">
                    <label for="nome">Nome do Lanche</label>
                    <input type="text" name="nome" id="nome" required>
                </div>

                <div class="input-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" required></textarea>
                </div>

                <div class="input-group">
                    <label for="preco">Preço</label>
                    <input type="text" name="preco" id="preco" required>
                </div>

                <div class="input-group">
                    <label for="imagem">Imagem</label>
                    <input type="file" name="imagem" id="imagem" accept="image/*">
                    <img id="imagem-preview" src="" alt="Imagem Atual" width="50" style="display:none;">
                </div>

                <button class="button" type="submit" name="editar_lanche">Atualizar Lanche</button>
            </form>
        </div>

        <!-- Botão de Voltar -->
        <a href="dashboard_admin.php" class="back-button">Voltar para o Início</a>
    </div>

    <script>
        function editarLanche(id, nome, descricao, preco, imagem) {
            // Exibe o formulário de edição
            document.getElementById('formulario-edicao').style.display = 'block';

            // Preenche os campos do formulário com os dados do lanche
            document.getElementById('id').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('descricao').value = descricao;
            document.getElementById('preco').value = preco;
            document.getElementById('imagem-preview').src = imagem;
            document.getElementById('imagem-preview').style.display ='block';
        }
    </script>
</body>
</html>