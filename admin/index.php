<?php include("header.php"); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <style>
        /* Reset e estilos globais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Container principal */
        .admin-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
            margin: 20px auto;
        }

        .admin-container h1 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #ff6600;
        }

        .admin-container p {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #666;
        }

        /* Links de navegação */
        .admin-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .admin-links a {
            text-decoration: none;
            background: #ff6600;
            color: white;
            padding: 12px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
            display: block;
        }

        .admin-links a:hover {
            background: #e44d26;
            transform: scale(1.05);
        }

        /* Responsividade */
        @media (max-width: 600px) {
            .admin-container {
                width: 95%;
                padding: 20px;
            }

            .admin-container h1 {
                font-size: 1.5rem;
            }

            .admin-links a {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Bem-vindo, Administrador!</h1>
        <p>Você tem acesso ao painel administrativo. Escolha uma das opções abaixo:</p>

        <nav class="admin-links">
            <a href="listar_lanches.php">Ver Lanches</a>
            <a href="cadastrar_lanche.php">Cadastrar Lanche</a>
            <a href="gerenciar_usuarios.php">Gerenciar Usuários</a>
        </nav>
    </div>
</body>
</html>

<?php include("footer.php"); ?>
