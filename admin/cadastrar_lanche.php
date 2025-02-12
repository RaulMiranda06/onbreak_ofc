<?php
include("conexao.php"); // Inclui a conexão com o banco de dados

// Função para exibir mensagens de erro ou sucesso
function exibirMensagem($mensagem, $tipo = 'error') {
    return "<div class='alert-message-$tipo'>$mensagem</div>";
}

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifique se os campos foram preenchidos
    if (isset($_POST['nome'], $_POST['descricao'], $_POST['preco'], $_POST['estoque'], $_FILES['imagem']) && 
        !empty($_POST['nome']) && !empty($_POST['descricao']) && !empty($_POST['preco']) && !empty($_POST['estoque']) && !empty($_FILES['imagem'])) {
        
        // Sanitizar os inputs
        $nome = htmlspecialchars(trim($_POST['nome']));
        $descricao = htmlspecialchars(trim($_POST['descricao']));
        $preco = $_POST['preco'];
        $estoque = $_POST['estoque']; // Obtém a quantidade de estoque
        $imagem = $_FILES['imagem'];

        // Verificar se o preço e estoque são válidos
        if (!is_numeric($preco) || $preco <= 0) {
            $mensagem = exibirMensagem('Por favor, insira um preço válido.', 'error');
        } elseif (!is_numeric($estoque) || $estoque < 0) {
            $mensagem = exibirMensagem('Por favor, insira uma quantidade de estoque válida.', 'error');
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
                            $stmt = $pdo->prepare("INSERT INTO lanches (nome, descricao, preco, estoque, imagem) VALUES (:nome, :descricao, :preco, :estoque, :imagem)");
                            $stmt->bindParam(':nome', $nome);
                            $stmt->bindParam(':descricao', $descricao);
                            $stmt->bindParam(':preco', $preco);
                            $stmt->bindParam(':estoque', $estoque); // Adicionando o estoque
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
        body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        max-width: 600px;
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
        margin: 0 20px; /* Adiciona um pequeno espaçamento lateral para não colidir com a borda */
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

    .button {
        width: 530px;
        padding: 15px;
        background-color: #f4511e;
        color: white;
        font-size: 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .button:hover {
        background-color: #e64a19;
        transform: scale(1.05);
    }

    .back-button {
        display: inline-block;
        width: 500px;
        padding: 15px;
        text-align: center;
        background-color: #1E88E5;
        color: white;
        font-size: 18px;
        text-decoration: none;
        border-radius: 6px;
        margin-top: 20px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .back-button:hover {
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

    /* Responsivo para telas de tablets e dispositivos menores */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
            max-width: 100%;
            margin: 0 10px; /* Ajusta as margens laterais para telas menores */
        }

        .button,
        .back-button {
            font-size: 16px;
            padding: 12px;
        }

        .input-group input,
        .input-group textarea {
            font-size: 14px;
        }

        h2 {
            font-size: 22px; /* Reduz o tamanho do título em telas menores */
        }
    }

    /* Responsivo para dispositivos móveis (menor que 480px) */
    @media (max-width: 480px) {
        .container {
            padding: 15px;
            margin: 0 5px; /* Pequena margem para smartphones */
        }

        .button,
        .back-button {
            font-size: 14px;
            padding: 10px;
        }

        .input-group input,
        .input-group textarea {
            font-size: 12px;
        }

        h2 {
            font-size: 20px; /* Ajusta o título para telas muito pequenas */
        }
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
                <label for="estoque">Estoque</label>
                <input type="number" name="estoque" id="estoque" min="0" required>
            </div>

            <div class="input-group">
                <label for="imagem">Imagem</label>
                <input type="file" name="imagem" id="imagem" accept="image/*" required>
            </div>

            <button class="button" type="submit">Cadastrar Lanche</button>
        </form>

        <!-- Botão para voltar ao dashboard -->
        <a href="index.php" class="back-button">Voltar para o Início</a>
    </div>
</body>
</html>
