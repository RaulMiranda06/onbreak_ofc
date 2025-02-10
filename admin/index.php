<?php
session_start();

// Inclui o cabeçalho da página
include('header.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login_admin.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../css/estilo.css"> <!-- Assumindo que o arquivo CSS está configurado -->
</head>
<body>
    <br><br><br><br><br><br><br><br><br>
    <div class="admin-container">
        <h1>Bem-vindo, Administrador!</h1>
        <p>Você tem acesso ao painel administrativo. Escolha uma das opções abaixo:</p>

        <nav class="admin-links">
            <a href="listar_lanches.php">Ver Lanches</a>
            <a href="cadastrar_lanche.php">Cadastrar Lanche</a>
            <a href="gerenciar_usuarios.php">Gerenciar Usuários</a>
        </nav>
    </div>

    <!-- Espaçamento foi removido de <br> e adicionado com CSS -->
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <!-- Inclui o rodapé da página -->
    <?php include('footer.php'); ?>
</body>
</html>
