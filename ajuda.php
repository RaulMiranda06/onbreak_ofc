<style>
:root{
  --dark: #0c0c0c;
  --white: #fff;
  --purple: #5959ff;
  --gray: #949494;
  --gray2: #cccccc;
}

body{
  background-color: var(--dark);
  font-family: Arial, Helvetica, sans-serif;
  color: var(--white);
  display: flex;
  justify-content: center;
  height: 100vh;
}

.box-questions{
  margin-top: 3rem;
  background-color: var(--white);
  color: var(--dark);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  max-height: 800px;
  width: 600px;
  border-radius: 10px;
  text-align: center;
}

.header{
  background-color: var(--purple);
  color: var(--white);
  border-radius: 10px 10px 0 0;
  font-size: 1.4rem;
}

.footer{
  background-color: var(--purple);
  border-radius: 0 0 10px 10px;
  padding: 1rem;
}

input{
  width: 50%;
  border: none;
  outline: none;
  padding: 10px;
  border-radius: 5px;
  font-size: 1rem;
}

button{
  border: none;
  border-radius: 5px;
  padding: 10px;
  font-size: 1rem;
}

button:hover{
  cursor: pointer;
  transition: .4s;
  background-color: var(--gray);
}

#history{
  padding: 1rem;
  overflow: auto;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  height: 100%;
}

.box-my-message{
  display: flex;
  justify-content: flex-end;
}

.box-response-message{
  display: flex;
  justify-content: flex-start;
}

.my-message,
.response-message{
  padding: 1rem;
  border-radius: 10px;
  color: white;
  margin: 0;
}

.my-message{
  text-align: right;
  background-color: var(--gray);
}

.response-message{
  text-align: left;
  background-color: var(--gray2);
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Perguntas e respostas - CHATGPT</title>
</head>
<body>
  <div class="box-questions">
    <div class="header">
      <p>Perguntas e respostas - CHATGPT</p>
    </div>
    <p id="status"></p>
    <div id="history">
      <!-- Aqui vai o chat gerado conforme as respostas -->
    </div>
    <div class="footer">
      <input type="text" id="message-input" placeholder="Pergunte aqui...">
      <button class="btn-submit" id="btn-submit" onclick="sendMessage()">Enviar</button>
    </div>
  </div>
</body>
<script src="main.js"></script>
</html>