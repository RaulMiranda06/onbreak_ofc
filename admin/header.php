<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnBreak Lanches</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <header>
        <div class="header-container">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">
                    <img src="../img/logo.png" alt="Logo OnBreak Lanches" />
                </a>
            </div>

            <!-- Botão do Menu Hambúrguer -->
            <div class="hamburger-menu" id="hamburger-menu" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </div>
            
            <!-- Navegação -->
            <nav class="nav-links" id="nav-links">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="perfil_admin.php">Meu Perfil</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>

            
    </header>

    <script>
        // Funcionalidade do menu hambúrguer
        const hamburger = document.getElementById('hamburger-menu');
        const navLinks = document.getElementById('nav-links');

        // Alterna a classe 'active' no menu ao clicar no botão de hambúrguer
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>
