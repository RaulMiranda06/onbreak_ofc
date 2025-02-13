<?php
// Configuração da conexão com o banco de dados
$host = 'localhost';
$dbname = 'sistema_lanche';
$username = 'root';
$password = '';

try {
    // Criando a conexão com PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Definindo o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
} catch (PDOException $e) {
    // Caso ocorra um erro, exibir mensagem
    echo "Erro na conexão: " . $e->getMessage();
}
?>
