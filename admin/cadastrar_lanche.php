<?php
include("../includes/conexao.php"); // Inclui a conexão com o banco de dados

// Função para exibir mensagens de erro ou sucesso
function exibirMensagem($mensagem, $tipo = 'error') {
    return "<div class='alert-message-$tipo'>$mensagem</div>";
}

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifique se os campos foram preenchidos
    if (isset($_POST['nome'], $_POST['descricao'], $_POST['preco'], $_FILES['imagem']) && 
        !empty($_POST['nome']) && !empty($_POST['descricao']) && !empty($_POST['preco']) && !empty($_FILES['imagem'])) {
        
        // Sanitizar os inputs
        $nome = htmlspecialchars(trim($_POST['nome']));
        $descricao = htmlspecialchars(trim($_POST['descricao']));
        $preco = $_POST['preco'];
        $imagem = $_FILES['imagem'];

        // Verificar se o preço é válido (número positivo)
        if (!is_numeric($preco) || $preco <= 0) {
            $mensagem = exibirMensagem('Por favor, insira um preço válido.', 'error');
        } else {
            // Verifique se ocorreu erro no upload do arquivo
            if ($imagem['error']) {
                $mensagem = exibirMensagem('Falha ao enviar o arquivo de imagem. Erro: ' . $imagem['error'], 'error');
            } else {
                // Verifique o tamanho do arquivo (máximo 5MB)
                if ($imagem['size'] > 5097152) {
                    $mensagem = exibirMensagem('O arquivo de imagem é muito grande. O tamanho máximo permitido é 5MB.', 'error');
                } else {
                    // Valide o tipo de arquivo (apenas imagens jpg e png)
                    $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
                    if ($extensao != 'jpg' && $extensao != 'png') {
                        $mensagem = exibirMensagem('Somente imagens JPG e PNG são permitidas.', 'error');
                    } else {
                        // Pasta para salvar as imagens
                        $pasta = "../uploads/";

                        // Gerar um nome único para a imagem
                        $novoNomeImagem = uniqid() . '.' . $extensao;
                        $path = $pasta . $novoNomeImagem;

                        // Tente mover o arquivo para a pasta
                        if (move_uploaded_file($imagem['tmp_name'], $path)) {
                            // Inserir dados no banco de dados
                            $stmt = $pdo->prepare("INSERT INTO lanches (nome, descricao, preco, imagem) VALUES (:nome, :descricao, :preco, :imagem)");
                            $stmt->bindParam(':nome', $nome);
                            $stmt->bindParam(':descricao', $descricao);
                            $stmt->bindParam(':preco', $preco);
                            $stmt->bindParam(':imagem', $novoNomeImagem); // Caminho da imagem no banco

                            // Executar a query e verificar sucesso
                            if ($stmt->execute()) {
                                $mensagem = exibirMensagem('Lanche cadastrado com sucesso!', 'success');
                            } else {
                                $mensagem = exibirMensagem('Falha ao cadastrar o lanche. Por favor, tente novamente.', 'error');
                            }
                        } else {
                            $mensagem = exibirMensagem('Falha ao mover o arquivo de imagem. Tente novamente.', 'error');
                        }
                    }
                }
            }
        }
    } else {
        $mensagem = exibirMensagem('Por favor, preencha todos os campos.', 'error');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Lanche</title>
    <style>
        /* Estilo do corpo da página */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container principal do formulário */
        .container {
            width: 100%;
            max-width: 420px;  /* Aumenta um pouco a largura */
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);  /* Sombra mais suave */
            box-sizing: border-box;
        }

        /* Título do formulário */
        h2 {
            text-align: center;
            color: #f4511e;
            margin-bottom: 20px;
            font-size: 26px;  /* Tamanho maior para destaque */
        }

        /* Estilo para o grupo de entradas */
        .input-group {
            margin-bottom: 20px;
        }

        /* Label do formulário */
        .input-group label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        /* Inputs e textareas do formulário */
        .input-group input, 
        .input-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        /* Estilo específico para textarea */
        .input-group textarea {
            resize: vertical;
        }

        /* Botão de ação principal */
        .button {
            width: 100%;
            padding: 15px;
            border: none;
            background-color: #f4511e;
            color: white;
            font-size: 18px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-sizing: border-box;
        }

        /* Efeito de hover no botão */
        .button:hover {
            background-color: #e64a19;
            transform: scale(1.05);
        }

        /* Estilo para mensagens de sucesso */
        .alert-message-success {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Estilo para mensagens de erro */
        .alert-message-error {
            padding: 15px;
            background-color: #f44336;
            color: white;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Botão de voltar para o dashboard */
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

        /* Efeito de hover no botão de voltar */
        .back-button:hover {
            background-color: #1565C0;
            transform: scale(1.05);
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastrar Lanche</h2>

        <!-- Exibir alertas de sucesso ou erro -->
        <?php if (isset($mensagem)): ?>
            <?php echo $mensagem; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" action="">
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
                <input type="file" name="imagem" id="imagem" accept="image/*" required>
            </div>

            <button class="button" type="submit">Cadastrar Lanche</button>
        </form>

        <!-- Botão para voltar ao dashboard -->
        <a href="dashboard_admin.php" class="back-button">Voltar para o Início</a>
    </div>
</body>
</html>
