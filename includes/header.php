<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnBreak Lanches</title>
    
    <!-- FontAwesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset e estilos gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Cabeçalho */
        header {
            background-color: #fff;
            padding: 15px 0;
            border-bottom: 2px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Logo */
        .logo img {
            max-height: 50px;
        }

        /* Navegação */
        .nav-links ul {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-links li a {
            text-decoration: none;
            color: #333;
            font-size: 1rem;
            transition: color 0.3s ease-in-out;
        }

        .nav-links li a:hover {
            color: #e44d26;
        }

        /* Ícone do Carrinho */
        .cart-container {
            text-decoration: none;
            font-size: 1.8rem;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">
                    <img src="img/logo.png" alt="Logo OnBreak Lanches">
                </a>
            </div>

            <!-- Navegação -->
            <nav class="nav-links">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="perfil.php">Meu Perfil</a></li>
                    <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>

            <!-- Ícone do Carrinho -->
            <a href="carrinho.php" class="cart-container">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </header>
</body>
</html>
