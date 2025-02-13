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
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Fundo claro com tons suaves de cinza */
    body {
        background: #f5f5f5; /* Alterando o fundo para um cinza claro */
        font-family: Arial, sans-serif;
    }

    /* Estilização do banner */
    .banner {
        width: 100%;
        height: 700px; /* Mantendo o tamanho */
    }

    /* Grid responsivo para os produtos */
    .product-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Grid responsivo */
        gap: 20px;
        padding: 15px;
        max-width: 1100px;
        margin: auto;
    }

    /* Estilo do cartão de produto */
    .product-card {
        border: 1px solid #ccc; /* Cor de borda mais suave */
        border-radius: 10px;
        text-align: center;
        padding: 15px;
        background: #ffffff;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        position: relative;
        min-height: 300px; /* Mantendo tamanho adequado */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Imagens dos produtos responsivas */
    .product-image {
        width: 100%;
        height: 180px; /* Altura ajustada */
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .product-image img:hover {
        transform: scale(1.05);
    }

    /* Nome do produto */
    .product-name {
        font-size: 1.2em;
        margin-top: 10px;
        color: #333; /* Mantendo cor escura para contraste */
        font-weight: bold;
    }

    /* Preço com azul suave */
    .product-price {
        color: #3b82f6; /* Alterado para azul suave */
        font-weight: bold;
        font-size: 1.1em;
        margin: 10px 0;
    }

    /* Botão de compra com cor de destaque */
    .btn-buy {
        display: inline-block;
        padding: 10px 16px;
        background-color: #3b82f6; /* Azul mais suave */
        color: white;
        font-size: 1em;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.2s ease-in-out;
    }

    .btn-buy:hover {
        background-color: #2563eb; /* Azul mais escuro no hover */
        transform: scale(1.05);
    }

    /* Grid para pedidos */
    .order-gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 pedidos por linha */
        gap: 20px;
        padding: 15px;
        max-width: 1100px;
        margin: auto;
    }

    /* Ajustes para os pedidos */
    .order-card {
        background: linear-gradient(to bottom, #a7c7ff, #6fa3f7); /* Gradiente azul claro */
        color: #333;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 280px; /* Ajuste de altura */
    }

    .order-card:hover {
        background: linear-gradient(to bottom, #86aef7, #4c82f2); /* Gradiente mais forte no hover */
    }

    /* Botão de pedido */
    .btn-order {
        display: inline-block;
        padding: 10px 16px;
        background-color: #6fa3f7; /* Azul suave */
        color: white;
        font-size: 1em;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.2s ease-in-out;
    }

    .btn-order:hover {
        background-color: #4c82f2; /* Azul mais forte no hover */
        transform: scale(1.05);
    }

    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .order-gallery {
            grid-template-columns: repeat(2, 1fr); /* 2 pedidos por linha em telas menores */
        }
    }

    @media (max-width: 480px) {
        .order-gallery {
            grid-template-columns: 1fr; /* 1 pedido por linha em telas muito pequenas */
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
    <br><br><br><br><br>
    <?php include("includes/footer.php"); ?>
</body>
</html>
