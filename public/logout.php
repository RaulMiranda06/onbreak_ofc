<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado como admin
if (isset($_SESSION['permissao']) && $_SESSION['permissao'] == 'admin') {
    // Redireciona para a página de login do admin
    header('Location: ../admin/login_admin.php');
    exit;
}

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar para a página inicial ou página de login
header('Location: ../public/login_usuario.php'); // Aqui você pode redirecionar para qualquer página
exit;
?>
