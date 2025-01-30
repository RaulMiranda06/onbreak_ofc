<?php
session_start();
include("includes/conexao.php");
include('includes/header.php');

// Query para pegar os dados dos produtos (lanches)
$query = "SELECT * FROM lanches";
$stmt = $pdo->query($query); // Executa a query
$lanches = $stmt->fetchAll(); // Pega todos os produtos do banco de dados


?>
<style>
        /* Estilos para a página */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .page-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 20px;
            margin-top: 50px;
        }

        .product-card {
            width: 200px;
            margin: 15px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-card img {
            height: auto;
            width: 100px;  /* Definindo uma largura fixa */
            max-width: 100%;  /* Garante que a largura não ultrapasse 100% do contêiner */
            border-radius: 6px;
            object-fit: cover;  /* Faz com que a imagem se ajuste sem distorção */
        }

        .product-info {
            margin-top: 10px;
        }

        .product-name {
            font-size: 18px;
            color: #333;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 16px;
            color: #f4511e;
            font-weight: bold;
        }

        .btn-buy {
            padding: 10px 20px;
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            text-decoration: none;
        }

        .btn-buy:hover {
            background-color: #1565c0;
        }

        .product-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Responsividade para dispositivos móveis */
        @media (max-width: 768px) {
            .product-card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carriho de compra</title>
    <h1>Carrinho de compra</h1>
    <div class="page-container">
        <div class="product-gallery">
            <?php foreach ($lanches as $lanche): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php 
                        // Verifica se a imagem do produto existe
                        $imagem_path = '/uploads/' . htmlspecialchars($lanche['imagem']);
                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagem_path)): ?>
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
                        <a href="carrinho.php" class="btn-buy">Comprar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</head>
<body>







<?php include('includes/footer.php'); ?>
</body>
</html>










