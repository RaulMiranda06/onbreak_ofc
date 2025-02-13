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
        background-color: #f0f4f8; /* Tom suave de azul claro para o fundo */
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    /* Container principal */
    .admin-container {
        background: #ffffff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 600px;
        width: 90%;
        margin: 20px auto;
        transition: transform 0.3s ease-in-out;
    }

    .admin-container:hover {
        transform: translateY(-5px);
    }

    .admin-container h1 {
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: #2c3e50; /* Azul escuro para o título */
    }

    .admin-container p {
        font-size: 1.1rem;
        margin-bottom: 25px;
        color: #7f8c8d; /* Cor cinza suave para o texto */
    }

    /* Links de navegação */
    .admin-links {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .admin-links a {
        text-decoration: none;
        background: #3498db; /* Cor azul vibrante para os links */
        color: white;
        padding: 14px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        display: block;
        text-align: center;
    }

    .admin-links a:hover {
        background: #2980b9; /* Azul mais escuro para hover */
        transform: scale(1.05);
    }

    .admin-links a:active {
        background: #1f618d; /* Azul ainda mais escuro no clique */
    }

    /* Responsividade */
    @media (max-width: 600px) {
        .admin-container {
            width: 95%;
            padding: 25px;
        }

        .admin-container h1 {
            font-size: 1.6rem;
        }

        .admin-links a {
            font-size: 1rem;
            padding: 12px;
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
            <a href="admin_pedidos.php">Gerenciar Pedidos</a>
        </nav>
    </div>
</body>
</html>

<?php include("footer.php"); ?>
