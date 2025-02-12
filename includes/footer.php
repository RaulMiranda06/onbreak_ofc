<!-- Includes/footer.php -->
<footer>
    <style>
        footer {
            background: #f8f9fa; /* Cor clara */
            color: #333; /* Texto escuro para contraste */
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            border-top: 2px solid #ddd; /* Linha sutil no topo */
        }

        .footer-container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-left, .footer-right {
            flex: 1;
            min-width: 250px;
            text-align: center;
        }

        .footer-right a {
            color: #007bff; /* Azul para links */
            text-decoration: none;
            font-weight: bold;
        }

        .footer-right a:hover {
            color: #0056b3; /* Azul mais escuro ao passar o mouse */
        }

        @media (max-width: 600px) {
            .footer-container {
                flex-direction: column;
            }
        }
    </style>

    <div class="footer-container">
        <!-- Parte esquerda do rodapé -->
        <div class="footer-left">
            <p>&copy; 2025 OnBreak Lanches. Todos os direitos reservados.</p>
        </div>

        <!-- Parte direita do rodapé -->
        <div class="footer-right">
            <p><a href="mailto:contato@onbreak.com">contato@onbreak.com</a></p>
        </div>
    </div>
</footer>

</body>
</html>
