<!DOCTYPE html>
<html lang="en">
<head>
    <title>IC 2024 | BSI 6</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de login</title>
    <style>
        /* Fonte e fundo principal */
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(to right, #441281, #915ad3);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #f5ad00;
        }
        
        /* Caixa de login */
        .login-container {
            background-color: rgba(68, 18, 129, 0.85);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Título de login */
        h1 {
            color: #f5ad00;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Inputs de texto */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            max-width: 300px;
            padding: 15px;
            border: none;
            outline: none;
            font-size: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            color: #441281;
            text-align: left; /* Alinha o texto à esquerda */
        }

        /* Cor do placeholder (mais escuro) */
        input::placeholder {
            color: #b3b3b3; /* Tom de cinza mais escuro para o placeholder */
        }
        
        /* Botão de envio */
        .inputSubmit {
            background-color: #f5ad00;
            border: none;
            padding: 15px;
            width: 100%;
            max-width: 200px;
            border-radius: 10px;
            color: #441281;
            font-size: 15px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .inputSubmit:hover {
            background-color: #d9d9d9;
        }

        /* Botão de retorno */
        .backButton {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #f5ad00;
            color: #441281;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .backButton:hover {
            background-color: #d9d9d9;
        }
    </style>
</head>
<body>
    <a href="home.php" class="backButton">Voltar</a>
    <div class="login-container">
        <h1>Login</h1>
        <form action="testLogin.php" method="POST">
            <input type="text" name="usuario" placeholder="Nome de Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <input class="inputSubmit" type="submit" name="submit" value="Entrar">
        </form>
    </div>
</body>
</html>
