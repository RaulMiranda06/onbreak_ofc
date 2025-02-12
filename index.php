<?php  
session_start();
include("includes/conexao.php");
include("includes/header.php");

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login_usuario.php");
    exit;
}

// Busca os lanches no banco de dados
$query = "SELECT id, nome, preco, imagem FROM lanches";
$stmt = $pdo->prepare($query);
$stmt->execute();
$lanches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venda de Lanches</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
    /* Estilização do banner */
.banner {
    width: 100%;
    height: 600px; /* Aumentado para telas grandes */
    object-fit: cover;
}

/* Ajuste do tamanho do banner em telas menores */
@media (max-width: 1024px) {
    .banner {
        height: 400px; /* Ajuste para tablets */
    }
}

@media (max-width: 768px) {
    .banner {
        height: 300px; /* Ajuste para celulares médios */
    }
}

@media (max-width: 480px) {
    .banner {
        height: 200px; /* Ajuste para celulares pequenos */
    }
}

/* Grid responsivo para os produtos */
.product-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Ajustado para melhor distribuição */
    gap: 25px;
    padding: 20px;
    max-width: 1200px; /* Aumentado para telas grandes */
    margin: auto;
}

/* Estilo do cartão de produto */
.product-card {
    border: 1px solid #ddd;
    border-radius: 12px; /* Maior borda para um design mais suave */
    text-align: center;
    padding: 20px;
    background: #fff;
    box-shadow: 0px 5px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

/* Efeito ao passar o mouse */
.product-card:hover {
    transform: scale(1.08);
}

/* Imagens dos produtos responsivas */
.product-image img {
    width: 100%;
    max-height: 180px; /* Aumentado para melhor visualização */
    object-fit: cover;
    border-radius: 10px;
}

/* Nome do produto */
.product-name {
    font-size: 1.3em; /* Aumentado para melhor destaque */
    margin-top: 12px;
}

/* Preço destacado */
.product-price {
    color: #e44d26;
    font-weight: bold;
    font-size: 1.1em;
}

/* Botão de compra */
.btn-buy {
    display: inline-block;
    margin-top: 12px;
    padding: 12px 18px;
    background-color: #e44d26;
    color: white;
    font-size: 1em;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s, transform 0.2s;
}

/* Efeito no botão */
.btn-buy:hover {
    background-color: #c5371b;
    transform: scale(1.07);
}

/* Ajustes responsivos para telas pequenas */
@media (max-width: 600px) {
    .product-gallery {
        grid-template-columns: 1fr;
        padding: 15px;
    }

    .btn-buy {
        width: 100%;
        font-size: 1.1em;
    }

    .product-image img {
        max-height: 160px;
    }
}

    </style>
</head>
<body>

    <img class="banner" src="img/banner.webp" alt="Banner de Lanches">
    <br><br>
    <div class="page-container">
        <div class="product-gallery">
            <?php foreach ($lanches as $lanche): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="uploads/<?php echo htmlspecialchars($lanche['imagem'] ?: 'default.png'); ?>" 
                            alt="Imagem de <?php echo htmlspecialchars($lanche['nome']); ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($lanche['nome']); ?></h3>
                        <p class="product-price">R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></p>
                        <a href="carrinho.php?action=add&id=<?php echo $lanche['id']; ?>" class="btn-buy">Comprar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>
</body>
</html>
