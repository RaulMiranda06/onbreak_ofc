<?php
$host = 'localhost';
$user = 'root';
$password = ''; // senha do banco de dados, está vazia por padrão no XAMPP
$dbname = 'sistema_lanche';

// Corrigido: Usar as variáveis corretas para a conexão
$mysqli = new mysqli($host, $user, $password, $dbname);

// Verifica se houve algum erro de conexão
if ($mysqli->connect_errno) {
    die("Erro na conexão: " . $mysqli->connect_error);
}
?>
