<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnBreak Lanches</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Reset e estilos gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Cabeçalho fixo */
        header {
            background-color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        /* Espaçamento para o conteúdo não ficar escondido sob o cabeçalho fixo */
        .content {
            margin-top: 80px;
            padding: 20px;
        }

        /* Logo */
        .logo img {
            height: 50px;
        }

        /* Navegação */
        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-links ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links ul li {
            display: inline;
        }

        .nav-links ul li a {
            text-decoration: none;
            color: #333;
            font-size: 1rem;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .nav-links ul li a:hover {
            color: #e44d26;
        }

        /* Ícone do Carrinho */
        .cart-container {
            font-size: 1.5rem;
            color: #333;
            margin-left: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .cart-container:hover {
            transform: scale(1.1);
            color: #e44d26;
        }

        /* Estilo do menu hambúrguer */
        .hamburger-menu {
            font-size: 24px;
            color: #333;
            cursor: pointer;
            display: none;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .nav-links {
                position: absolute;
                top: 60px;
                right: 0;
                width: 100%;
                height: 0;
                background: #fff;
                text-align: center;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                transition: height 0.4s ease-in-out;
            }

            .nav-links.active {
                height: 200px;
            }

            .nav-links ul {
                flex-direction: column;
                gap: 15px;
                padding: 10px 0;
                opacity: 0;
                transition: opacity 0.4s ease-in-out;
            }

            .nav-links.active ul {
                opacity: 1;
            }

            .hamburger-menu {
                display: block;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="../img/logo.png" alt="Logo OnBreak Lanches">
            </a>
        </div>

        <!-- Botão do Menu Hambúrguer -->
        <div class="hamburger-menu" id="hamburger-menu" aria-label="Abrir menu">
            <i class="fas fa-bars"></i>
        </div>
        
        <!-- Navegação -->
        <nav class="nav-links" id="nav-links">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="perfil_admin.php">Meu Perfil</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <!-- Conteúdo principal -->
    <script>
        // Funcionalidade do menu hambúrguer
        const hamburger = document.getElementById('hamburger-menu');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>
