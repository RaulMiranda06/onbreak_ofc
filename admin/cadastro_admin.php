<?php
session_start(); // Inicia a sessão

include('conexao.php'); // Corrigido para "include"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validação de campos
    if (empty($email) || empty($senha) || empty($confirmar_senha)) {
        $_SESSION['error'] = "Por favor, preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "E-mail inválido.";
    } elseif ($senha !== $confirmar_senha) {
        $_SESSION['error'] = "As senhas não coincidem.";
    } else {
        // Verifica se o e-mail já existe no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['error'] = "Este e-mail já está cadastrado.";
        } else {
            // Criptografa a senha
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

            // Insere o novo usuário no banco de dados
            $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha) VALUES (:email, :senha)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Usuário cadastrado com sucesso!";
                header('Location: login_admin.php'); // Redireciona para a página de login
                exit();
            } else {
                $_SESSION['error'] = "Erro ao cadastrar o usuário.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
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
        <h2>Cadastrar Administrador</h2>

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
            <div class="input-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" required>
            </div>

            <button class="button" type="submit">Cadastrar</button>
        </form>

        <div class="footer">
            <p>Já tem uma conta? <a href="login_admin.php">Faça login aqui</a></p>
        </div>
    </div>
</body>
</html>
