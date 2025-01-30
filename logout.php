<?php
session_start();
session_destroy(); // Destrói a sessão
header('Location: login_usuario.php'); // Redireciona para o login
exit();
?>
