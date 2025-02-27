<?php
include('conexao.php');

if (isset($_POST['email']) || isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        echo '<div class="alert alert-danger" role="alert">Preencha o email</div>';
    } else if (strlen($_POST['senha']) == 0) {
        echo '<div class="alert alert-danger" role="alert">Preencha a senha</div>';
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sqli_code = "SELECT u.id, u.nome, u.id_permissoes_usuario, p.perfil
                      FROM usuarios u 
                      JOIN permissoes p ON u.id_permissoes_usuario = p.id
                      WHERE u.email = '$email' AND u.senha = '$senha'";

        $sqli_query = $mysqli->query($sqli_code);

        $quantidade = $sqli_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sqli_query->fetch_assoc();

            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['nivel'] = $usuario['id_permissoes_usuario'];
            $_SESSION['perfil'] = $usuario['perfil'];

            if ($_SESSION['nivel'] > 2) {
                header('location: painel.php');
            } else {
                header('location: revista.php');
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Usuário não encontrado</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>

    <style>
        /* Fundo animado */
        body {
            background: linear-gradient(45deg, #6a11cb, #2575fc, #6a11cb, #2575fc);
            background-size: 400% 400%;
            animation: gradient 10s ease infinite;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Container principal */
        .login-container {
            display: flex;
            align-items: stretch;
            width: 600px;
            max-width: 90%;
            height: 350px; /* Altura fixa */
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
        }

        /* Primeiro container (formulário) */
        .container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .container:first-child {
            background: #f8f9fa;
        }

        /* Ícone */
        i {
            font-size: 50px;
            margin-bottom: 10px;
        }

        /* Segundo container (imagem) */
        .container:last-child {
            background: #E0E2EF;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container img {
            width: auto;
            height: 80%;
            max-height: 250px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="container">
            <i class="bi bi-person-circle"></i>
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" id="senha">
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
        <div class="container">
            <img src="./images/cute-girl-lendo-livro-cartoon.avif" alt="Mulher Cartoon com livro">
        </div>
    </div>

</body>
</html>
