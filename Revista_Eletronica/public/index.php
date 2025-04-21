<?php
include('../config/conexao.php');

if (isset($_POST['email']) || isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0 && strlen($_POST['senha']) == 0) {
        echo "<script>
                    document.addEventListener('DOMContentLoaded', () => {
                    var myModal = new bootstrap.Modal(document.getElementById('faltaModal'));
                    myModal.show();
                    setTimeout(() => {
                        myModal.hide();
                    }, 3000);
                    });
                </script>";
    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sqli_code = "SELECT u.id, u.nome, u.id_permissoes_usuario, u.perfil_foto, u.bio, p.perfil
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
            $_SESSION['foto'] = $usuario['perfil_foto'];
            $_SESSION['biografia'] = $usuario['bio'];

            echo "<script>
                    document.addEventListener('DOMContentLoaded', () => {
                        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                        myModal.show();
                        setTimeout(() => {
                            window.location.href = '../views/revista.php';
                        }, 3000);
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', () => {
                    var myModal = new bootstrap.Modal(document.getElementById('failModal'));
                    myModal.show();
                    setTimeout(() => {
                        myModal.hide();
                    }, 3000);
                    });
                </script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./login.css?v=1.1">
    <title>Login</title>
</head>

<body>



    <div class="descricao">
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>

        <div class="container">
            <h1>Bem Vindo</h1>
            <img src="../assets/FlowUp.png" alt="">
            <p>A Flow.UP está criando uma revista digital para alunos compartilharem seus conhecimentos, curiosidades e ideias. Nosso objetivo é promover uma troca de experiências e aprendizado entre todos, criando um espaço criativo e colaborativo.</p>
        </div>
    </div>



    <div class="login-container" id="login-container">
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
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Lembrar Senha</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>




    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-body text-center">
                    <div class="check-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h1 class="yeah-text">Yeah!</h1>
                    <p class="success-message">Login realizado com sucesso</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="failModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-fail">
                <div class="modal-body text-center">
                    <div class="fail-circle">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h1 class="fail-text">Ops!</h1>
                    <p class="fail-message">Login Falhou, tente novamente</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="faltaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-fail">
                <div class="modal-body text-center">
                    <div class="fail-circle">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h1 class="fail-text">Ops!</h1>
                    <p class="fail-message">Alguns campos estão incompletos</p>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let login = document.getElementById('login-container');
        for (let i = 0; i < 7; i++) {
            let star = document.createElement('i');
            star.classList.add('fas', 'fa-star', 'star');
            login.appendChild(star);
        }
    </script>
</body>

</html>