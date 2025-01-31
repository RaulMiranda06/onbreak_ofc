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
    <link rel="stylesheet" href="css/estilo.css">
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            height: auto;
            max-width: 100px;  /* Definindo uma largura fixa */
            border-radius: 5px;
            object-fit: cover;  /* Faz com que a imagem se ajuste sem distorção */
        }

        .product-info {
            margin-top: 10px;
        }

        .product-name {
            font-size: 15px;
            color: #333;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .product-price {
            font-size: 15px;
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
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-buy:hover {
            background-color: #1565c0;
        }

        .product-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .banner_principal {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .banner-item img {
            height: 80px;
            margin: 0 10px;
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
