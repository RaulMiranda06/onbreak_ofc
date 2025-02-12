<?php
session_start();
include('../includes/conexao.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Pega os dados do usuário
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuário não encontrado!");
    }

    // Atualiza os dados do usuário
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE clientes SET email = :email, senha = :senha WHERE id = :id");
            $stmt->bindParam(':senha', $senha_hash);
        } else {
            $stmt = $pdo->prepare("UPDATE clientes SET email = :email WHERE id = :id");
        }
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Usuário atualizado com sucesso!";
            header('Location: gerenciar_usuarios.php');
            exit();
        } else {
            $_SESSION['error'] = "Erro ao atualizar o usuário.";
        }
    }
} else {
    die("ID do usuário não fornecido!");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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

        /* Responsividade para telas pequenas */
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            .button {
                font-size: 18px;
                padding: 12px;
            }

            .input-group input {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                max-width: 100%;
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }

            .button {
                font-size: 16px;
                padding: 10px;
            }

            .input-group input {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Usuário</h2>

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
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>
        <div class="input-group">
            <label for="senha">Nova Senha</label>
            <input type="password" name="senha" id="senha">
        </div>
        <button class="button" type="submit">Atualizar</button>
    </form>
</div>

</body>
</html>
