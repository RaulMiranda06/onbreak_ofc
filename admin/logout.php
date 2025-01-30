<?php
session_start();
session_destroy(); // Destrói a sessão
header('Location: login_admin.php'); // Redireciona para o login
exit();
?>