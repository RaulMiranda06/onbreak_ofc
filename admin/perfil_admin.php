<?php
session_start();

include('conexao.php');

// Obtém o ID do usuário logado a partir da sessão
$usuario_id = $_SESSION['usuario_id'];

// Busca os dados do usuário na tabela 'clientes'
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $usuario_id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe
if (!$usuario) {
    $_SESSION['error'] = "Usuário não encontrado.";
    header('Location: login_admin.php');
    exit();
}

// Verifica se o formulário foi enviado para editar o perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Verifica se as senhas coincidem
    if (!empty($senha) && $senha !== $confirmar_senha) {
        $_SESSION['error'] = "As senhas não coincidem.";
    } else {
        // Se a senha foi fornecida, criptografa a nova senha
        $senha_hash = !empty($senha) ? password_hash($senha, PASSWORD_BCRYPT) : $usuario['senha'];
        
        // Atualiza os dados do usuário no banco de dados na tabela 'clientes'
        $stmt = $pdo->prepare("UPDATE clientes SET email = :email, senha = :senha WHERE id = :id");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $usuario_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Perfil atualizado com sucesso!";
            // Atualiza a sessão com o novo e-mail (se for alterado)
            $_SESSION['usuario_email'] = $email;
            // Recarrega as informações do usuário após a atualização
            $usuario['email'] = $email;
        } else {
            $_SESSION['error'] = "Erro ao atualizar o perfil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
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
            max-width: 500px;
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

        .button-back {
            background-color: #2196F3;
            width: 100%;
            margin-top: 20px;
        }

        .button-back:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Perfil do Usuário</h2>

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

        <!-- Exibição dos dados do usuário -->
        <h3>Informações do Perfil</h3>
        <div class="input-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
        </div>

        <form method="POST">
            <h3>Editar Perfil</h3>
            <div class="input-group">
                <label for="email">Novo E-mail</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="input-group">
                <label for="senha">Nova Senha (Deixe em branco para não alterar)</label>
                <input type="password" name="senha" id="senha">
            </div>

            <div class="input-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha">
            </div>

            <button class="button" type="submit">Salvar Alterações</button>
        </form>
        
        <form action="index.php">
            <button class="button button-back" type="submit">Voltar ao Início</button>
        </form>
        
        <div class="footer">
            <p><a href="logout.php">Sair</a></p>
        </div>

        <!-- Botão para voltar ao início -->
        
    </div>

</body>
</html>
