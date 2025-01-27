<?php
session_start(); // Inicia a sessão

// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'sistema_lanche';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação de campos
    if (empty($email) || empty($senha)) {
        $_SESSION['error'] = "Por favor, preencha todos os campos.";
    } else {
        // Verifica se o e-mail existe no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // A senha está correta
            $_SESSION['usuario_id'] = $usuario['id']; // Armazena o ID do usuário na sessão
            $_SESSION['usuario_email'] = $usuario['email']; // Armazena o e-mail do usuário na sessão
            $_SESSION['permissao'] = $usuario['permissao']; // Armazena a permissão do usuário (admin ou usuario)
            
            // Redireciona para a página principal (pode ser uma área restrita)
            header('Location: dashboard_admin.php');
            exit();
        } else {
            $_SESSION['error'] = "E-mail ou senha incorretos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #f4511e;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-size: 15px;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .button {
            width: 100%;
            padding: 15px;
            border: none;
            background-color: #f4511e;
            color: white;
            font-size: 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            box-sizing: border-box;
        }

        .button:hover {
            background-color: #e64a19;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer a {
            color: #f4511e;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
        }

        .alert-error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Adminstrador</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-error'>".$_SESSION['error']."</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
            unset($_SESSION['success']);
        }
        ?>

        <form method="POST">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
            </div>

            <button class="button" type="submit">Entrar</button>
        </form>

        <div class="footer">
            <p>Não tem uma conta? <a href="cadastro_admin.php">Cadastre-se aqui</a></p>
        </div>
    </div>
</body>
</html>
