<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnBreak Lanches</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <!-- Logo -->
            <div class="logo">
                <a href="../public/index.php">
                    <img src="../img/logo.png" alt="Logo OnBreak Lanches" />
                </a>
            </div>

            <!-- Botão do Menu Hambúrguer -->
            <div class="hamburger-menu" id="hamburger-menu" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </div>
            
            <!-- Navegação -->
            <nav class="nav-links" id="nav-links">
                <ul>
                    <li><a href="../public/index.php">Home</a></li>
                    <li><a href="perfil.php">Meu Perfil</a></li>
                    <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
                    <li><a href="../public/index.php">Formas de Pagamentos</a></li>
                    <li><a href="minhas_avaliacoes.php">Minhas Avaliações</a></li>
                    <li><a href="ajuda.php">Ajuda</a></li>
                    <li><a href="../public/logout.php">Sair</a></li>
                </ul>
            </nav>

            <!-- Ícone do Carrinho Fora da Lista -->
            <div class="cart-icon">
                <a href="../public/carrinho.php">
                    <i class="fas fa-shopping-cart"></i> <!-- Ícone de carrinho do Font Awesome -->
                </a>
            </div>
        </div>
    </header>

    <div class="banner_principal">
        <div class="banner-content">
            <div class="banner-item">
                <img src="../img/sucologo.png" alt="Logo da Sucologo, marca de sucos da OnBreak Lanches">
            </div>
            <div class="banner-item">
                <img src="../img/coxinhalogo.png" alt="Logo da CoxinhaLogo, marca de coxinhas da OnBreak Lanches">
            </div>
        </div>
    </div>

    <script>
        // Funcionalidade do menu hambúrguer
        const hamburger = document.getElementById('hamburger-menu');
        const navLinks = document.getElementById('nav-links');

        // Alterna a classe 'active' no menu ao clicar no botão de hambúrguer
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>
