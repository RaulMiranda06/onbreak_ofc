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

<style>
    #home {
    display: flex;
    min-height: calc(100vh - 91px);
    position: relative;
}

#cta {
    width: 35%;
    display: flex;
    flex-direction: column;
    gap: 28px;
    margin-top: 5%;
}

#cta .title {
    font-size: 4rem;
    color: var(--color-neutral-1);;
}

#cta .title span {
    color: var(--color-primary-6);
}

#cta .description {
    font-size: 1.2rem;
}

#cta_buttons {
    display: flex;
    gap: 24px;
}

#cta_buttons a {
    text-decoration: none;
    color: var(--color-neutral-1);;
}

#phone_button {
    display: flex;
    gap: 8px;
    align-items: center;
    background-color: #ffffff;
    padding: 8px 14px;
    font-weight: 500;
    box-shadow: 0px 0px 12px 4px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

#phone_button button {
    box-shadow: none;
}

#banner {
    display: flex;
    align-items: start;
    justify-content: end;
    width: 70%;
    z-index: 2;
}

#banner img {
    height: 100%;
    width: fit-content;
}

.shape {
    background-color: var(--color-primary-2);
    width: 50%;
    height: 100%;
    position: absolute;
    border-radius: 40% 30% 0% 20%;
    top: 0;
    right: 0;
    z-index: -2;
}

@media screen and (max-width: 1170px) {
    #home {
        min-height: 100%;
        padding-top: 0px;
    }

    #banner,
    #banner img,
    #home .shape {
        display: none;
    }

    #cta {
        width: 100%;
        text-align: center;
        align-items: center;
    }
}

@media screen and (max-width: 450px) {
    #phone_button button {
        display: none;
    }
}

header {
    width: 100%;
    padding: 28px 8%;
    position: sticky;
    top: 0;
    background-color: var(--color-primary-1);
    z-index: 3;
}

#navbar {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

#nav_logo {
    font-size: 24px;
    color: var(--color-primary-6);
}

#nav_list {
    display: flex;
    list-style: none;
    gap: 48px;
}

.nav-item a {
    text-decoration: none;
    color: #1d1d1dad;
    font-weight: 600;
}

.nav-item.active a {
    color: var(--color-neutral-1);
    border-bottom: 3px solid var(--color-primary-4);
}

#mobile_btn {
    display: none;
}

#mobile_menu {
    display: none;
}

@media screen and (max-width: 1170px) {
    #nav_list,
    #navbar .btn-default {
        display: none;
    }

    #mobile_btn {
        display: block;
        border: none;
        background-color: transparent;
        font-size: 1.5rem;
        cursor: pointer;
    }

    #mobile_menu.active {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #mobile_nav_list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin: 12px 0px;
    }

    #mobile_nav_list .nav-item {
        list-style: none;
        text-align: center;
    } 
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
@import url('header.css');
@import url('home.css');
@import url('menu.css');
@import url('testimonials.css');
@import url('footer.css');

:root {
    --color-primary-1: #fff9ea;
    --color-primary-2: #ffe8b4;
    --color-primary-3: #f8d477;
    --color-primary-4: #ffe100;
    --color-primary-5: #ffcb45;
    --color-primary-6: #e9a209;

    --color-neutral-0: #fff;
    --color-neutral-1: #1d1d1d;
}

* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    background-color: var(--color-primary-1);
}

section {
    padding: 28px 8%;
}

.btn-default {
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-primary-5);
    border-radius: 12px;
    padding: 10px 14px;
    font-weight: 600;
    box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: background-color .3s ease;
}

.btn-default:hover {
    background-color: var(--color-primary-3);
}

.social-media-buttons {
    display: flex;
    gap: 18px;
}

.social-media-buttons a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 40px;
    background-color: var(--color-neutral-0);
    font-size: 1.25rem;
    border-radius: 10px;
    text-decoration: none;
    color: var(--color-neutral-1);;
    box-shadow: 0px 0px 12px 4px rgba(0, 0, 0, 0.1);
    transition: box-shadow .3s ease;
}

.social-media-buttons a:hover {
    box-shadow: 0px 0px 12px 8px rgba(0, 0, 0, 0.1);
}

.section-title {
    color: var(--color-primary-6);
    font-size: 1.563rem;
}

.section-subtitle {
    font-size: 2.1875rem;
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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="src/styles/styles.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Landing page</title>
</head>
<body>

    <main id="content">
        <section id="home">
            <div class="shape"></div>
            <div id="cta">
                <h1 class="title">
                    O sabor que vai até
                    <span>você</span>
                </h1>

                <p class="description">
                   OnBreak veio para ficar
                </p>


 </div>
        </section>
    </main>
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
    <script src="src/javascript/script.js"></script>
</body>
</html>