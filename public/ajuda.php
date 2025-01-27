<?php
// Recebe a mensagem do usuário
if (isset($_POST['message'])) {
    $userMessage = strtolower(trim($_POST['message']));

    // Lógica simples de resposta baseada em palavras-chave
    if (strpos($userMessage, 'olá') !== false) {
        echo "Olá! Como posso ajudá-lo?";
    } elseif (strpos($userMessage, 'como vai') !== false) {
        echo "Estou bem, obrigado! E você?";
    } elseif (strpos($userMessage, 'tchau') !== false) {
        echo "Tchau! Até logo!";
    } else {
        echo "Desculpe, não entendi sua mensagem.";
    }
}
?>

<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

.chat-container {
    width: 300px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.chat-box {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.user-message, .bot-message {
    margin: 5px 0;
    padding: 10px;
    border-radius: 5px;
}

.user-message {
    background-color: #aef;
    text-align: right;
}

.bot-message {
    background-color: #eef;
    text-align: left;
}

input[type="text"] {
    width: 75%;
    padding: 10px;
    margin-right: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    padding: 10px;
    border-radius: 5px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}


</style>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Simples</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-box" id="chat-box">
            <!-- A conversa será exibida aqui -->
        </div>
        <input type="text" id="user-input" placeholder="Digite sua mensagem..." />
        <button onclick="sendMessage()">Enviar</button>
    </div>

    <script>
        function sendMessage() {
            var userMessage = document.getElementById('user-input').value;
            var chatBox = document.getElementById('chat-box');

            if (userMessage.trim() === "") return;

            // Exibir a mensagem do usuário
            chatBox.innerHTML += "<div class='user-message'>" + userMessage + "</div>";

            // Enviar a mensagem para o PHP para obter a resposta
            fetch('chatbot.php', {
                method: 'POST',
                body: new URLSearchParams({ 'message': userMessage }),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(response => response.text())
            .then(botResponse => {
                chatBox.innerHTML += "<div class='bot-message'>" + botResponse + "</div>";
                chatBox.scrollTop = chatBox.scrollHeight;
            });

            // Limpar o campo de entrada
            document.getElementById('user-input').value = '';
        }
    </script>
</body>
</html>