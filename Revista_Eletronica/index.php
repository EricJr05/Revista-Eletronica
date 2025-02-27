<?php

include('conexao.php');

if (isset($_POST['email']) || isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        echo 'Preencha o email ';
    } else if (strlen($_POST['senha']) == 0) {
        echo 'Preencha a senha';
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
            echo 'Ususário não encontrado';
        }
    }
}


?>




<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form method="POST">
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email">
        </p>
        <p>
            <label for="senha">Senha:</label>
            <input type="password" name="senha">
        </p>
        <p>
            <button type="submit">Logar</button>
        </p>
    </form>
</body>

</html>