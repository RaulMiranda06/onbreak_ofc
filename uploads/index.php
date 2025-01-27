<?php

include("conexao.php");

if (isset($_FILES['arquivo'])) {
    $arquivo = $_FILES['arquivo'];

    // Verifica se ocorreu erro no upload do arquivo
    if ($arquivo['error']) {
        die('Falha ao enviar o arquivo');
    }

    // Limita o tamanho do arquivo (máximo de 2MB)
    if ($arquivo['size'] > 2097152) {
        die('Arquivo muito grande! O tamanho máximo é 2MB.');
    }

    // Pasta onde o arquivo será armazenado
    $pasta = "arquivos/";

    // Obtém o nome original do arquivo
    $nomeDoArquivo = $arquivo["name"];

    // Gera um nome único para o arquivo usando uniqid() e adiciona a extensão
    $novoNomeDoArquivo = uniqid();

    // Obtém a extensão do arquivo original
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    // Verifica se o arquivo tem uma das extensões permitidas (jpg ou png)
    if ($extensao != "jpg" && $extensao != "png") {
        die('Tipo de arquivo não aceito. Apenas imagens JPG e PNG são permitidas.');
    }

    // Cria o caminho final do arquivo
    $path = $pasta . $novoNomeDoArquivo . '.' . $extensao;

    // Move o arquivo da pasta temporária para o destino
    $deu_certo = move_uploaded_file($arquivo['tmp_name'], $path);

    if ($deu_certo) {
        $mysqli -> query("INSERT INTO uploads (nome, path) VALUES ('$nomeDoArquivo', '')") or die($mysqli -> error);
        echo "<p> arquivo enviado com sucesso </p>";
    } else {
        echo "Falha ao enviar o arquivo.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivo</title>
</head>
<body>
    <h2>Enviar Arquivo</h2>
    <form method="POST" enctype="multipart/form-data" action="">
        <p><label for="arquivo">Selecione o arquivo:</label></p>
        <p><input name="arquivo" type="file" required></p>
        <button type="submit">Enviar arquivo</button>
    </form>
</body>
</html>
