<?php
session_start(); // Inicia a sessão


// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar para a página inicial ou página de login
header('Location: ../admin/login_admin.php'); // Aqui você pode redirecionar para qualquer página
exit;
?>
