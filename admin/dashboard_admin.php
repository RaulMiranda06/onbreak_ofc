<?php
session_start();

// Verifica se o usuário está logado e tem permissão de admin
if ($_SESSION['permissao'] != 'admin') {
    // Se não for admin, redireciona para a página inicial
    header('Location: ../public/index.php');
    exit();
}

// Inclui o cabeçalho da página
include('../includes/header.php');
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
    <div class="admin-container">
        <h1>Bem-vindo, Administrador!</h1>
        <p>Você tem acesso ao painel administrativo. Escolha uma das opções abaixo:</p>

        <div class="admin-links">
            <a href="listar_lanches.php">Ver Lanches</a>
            <a href="cadastrar_lanche.php">Cadastrar Lanche</a>
            <a href="gerenciar_usuarios.php">Gerenciar Usuários</a>
        </div>
    </div>


    <br><br><br><br><br><br><br><br><br><br><br>
    <!-- Inclui o rodapé da página -->
    <?php include('../includes/footer.php'); ?>  
</body>
</html>
