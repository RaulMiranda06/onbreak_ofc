<?php
session_start(); // Inicia a sessão

// Inclui a conexão com o banco de dados
include("includes/conexao.php");
include('includes/header.php');

// Query para pegar os dados dos lanches
$query = "SELECT * FROM lanches";
$stmt = $pdo->query($query); // Executa a query
$lanches = $stmt->fetchAll(); // Pega todos os produtos do banco de dados

// Inicializa o carrinho caso não exista na sessão
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venda de Lanches</title>

    <!-- CSS Interno -->
    <style>
        /* Resetando alguns estilos padrões */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Definindo a fonte padrão */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Estilo para o container de produtos */
        .page-container {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .product-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); /* Menor largura para os cards */
            gap: 15px;
            max-width: 1000px; /* Diminuir a largura máxima da galeria */
            width: 100%;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Diminuir o tamanho da sombra */
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .product-card:hover {
            transform: scale(1.02); /* Menor escala no hover */
        }

        .product-image img {
            width: 100%;
            height: 150px; /* Reduzir a altura da imagem */
            object-fit: cover;
        }

        .product-info {
            padding: 10px; /* Menos padding */
            text-align: center;
        }

        .product-name {
            font-size: 1em; /* Menor tamanho da fonte */
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 1em; /* Menor tamanho da fonte */
            color: #e53935;
            margin-bottom: 12px;
        }

        .btn-buy {
            display: inline-block;
            background-color: #e53935;
            color: white;
            padding: 8px 15px; /* Menos padding */
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .btn-buy:hover {
            background-color: #c62828;
        }

    </style>
</head>
<body>
    
    <div class="banner_principal">
        <div class="banner-content">
            <div class="banner-item">
                <img src="img/sucologo.png" alt="Logo da Sucologo, marca de sucos da OnBreak Lanches">
            </div>
            <div class="banner-item">
                <img src="img/coxinhalogo.png" alt="Logo da CoxinhaLogo, marca de coxinhas da OnBreak Lanches">
            </div>
        </div>
    </div>

    <div class="page-container">
        <div class="product-gallery">
            <?php foreach ($lanches as $lanche): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php 
                        // Verifica se a imagem do produto existe
                        $imagem_path = '/uploads/' . htmlspecialchars($lanche['imagem']);
                        $full_path = $_SERVER['DOCUMENT_ROOT'] . $imagem_path;
                        if (isset($lanche['imagem']) && !empty($lanche['imagem']) && file_exists($full_path)): ?>
                            <!-- Exibe a imagem do produto se ela existir -->
                            <img src="<?php echo $imagem_path; ?>" alt="Imagem do Produto">
                        <?php else: ?>
                            <!-- Caso a imagem não exista, exibe uma imagem padrão -->
                            <img src="/uploads/default.png" alt="Imagem padrão">
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($lanche['nome']); ?></h3>
                        <p class="product-price">R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></p>
                        <!-- Link que adiciona o item ao carrinho -->
                        <a href="carrinho.php?action=add&id=<?php echo $lanche['id']; ?>" class="btn-buy">Comprar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

</body>
</html>
