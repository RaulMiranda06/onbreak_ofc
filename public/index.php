<?php
// Inclui a conexão com o banco de dados
include("../includes/conexao.php");
include('../includes/header.php');

// Query para pegar os dados dos lanches
$query = "SELECT * FROM lanches";
$stmt = $pdo->query($query); // Executa a query
$lanches = $stmt->fetchAll(); // Pega todos os produtos do banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venda de Lanches</title>
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
            width: 100%;
            height: auto;
            border-radius: 6px;
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
</head>
<body>

    <div class="page-container">
        <div class="product-gallery">
            <?php foreach ($lanches as $lanche): ?>
                <div class="product-card">
                    <div class="product-image">
                        <!-- Exibe a imagem do produto -->
                        <img src="uploads/<?php echo htmlspecialchars($lanche['imagem']); ?>" alt="Imagem do Produto">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($lanche['nome']); ?></h3>
                        <p class="product-price">R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></p>
                        <a href="#" class="btn-buy">Comprar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

</body>
</html>
