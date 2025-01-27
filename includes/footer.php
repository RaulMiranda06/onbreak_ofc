<!-- Includes/footer.php -->
<footer>
    <div class="footer-container">
        <!-- Parte esquerda do rodapé -->
        <div class="footer-left">
            <p>&copy; 2025 OnBreak Lanches. Todos os direitos reservados.</p>

            <!-- Mensagem personalizada se o usuário for admin -->
            <?php if (isset($_SESSION['permissao']) && $_SESSION['permissao'] == 'admin'): ?>
                <p>Você está logado como administrador.</p>
            <?php endif; ?>
        </div>

        <!-- Parte direita do rodapé -->
        <div class="footer-right">
            <p><a href="mailto:contato@onbreak.com">contato@onbreak.com</a></p>
        </div>
    </div>
</footer>

</body>
</html>
