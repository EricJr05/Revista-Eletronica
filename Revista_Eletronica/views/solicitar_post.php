<?php
include('../config/conexao.php');
include('../middleware/protect_student.php');

if (!empty($_POST['titulo']) && !empty($_POST['subtitulo']) && !empty($_POST['conteudo']) && !empty($_POST['tema'])) {
    $titulo = $mysqli->real_escape_string($_POST['titulo']);
    $subtitulo = $mysqli->real_escape_string($_POST['subtitulo']);
    $tema = $mysqli->real_escape_string($_POST['tema']);
    $id_usuario_solicitacoes = $_SESSION['id'];

    $grupo_query = $mysqli->query("SELECT MAX(grupo) as max_grupo FROM posts");
    $grupo_row = $grupo_query->fetch_assoc();
    $novo_grupo = $grupo_row['max_grupo'] + 1;

    foreach ($_POST['conteudo'] as $index => $conteudo) {
        $conteudo = $mysqli->real_escape_string($conteudo);
        $file_name = basename($_FILES['img']['name'][$index]);
        $tempname = $_FILES['img']['tmp_name'][$index];
        $folder = __DIR__ . '/../images/' . $file_name;

        if (move_uploaded_file($tempname, $folder)) {
            $sqli_code = "INSERT INTO posts (id_usuario_solicitacoes, titulo, subtitulo, conteudo, tema, status, data_solicitacao, img, grupo) 
                          VALUES ('$id_usuario_solicitacoes', '$titulo', '$subtitulo', '$conteudo', '$tema', 'pendente', NOW(), '$file_name', '$novo_grupo')";
        } else {
            $sqli_code = "INSERT INTO posts (id_usuario_solicitacoes, titulo, subtitulo, conteudo, tema, status, data_solicitacao, grupo) 
                          VALUES ('$id_usuario_solicitacoes', '$titulo', '$subtitulo', '$conteudo', '$tema', 'pendente', NOW(), '$novo_grupo')";
        }

        $sqli_query = $mysqli->query($sqli_code);
        if (!$sqli_query) {
            echo 'Erro ao Enviar: ' . $mysqli->error;
        } else {
            echo '
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById("successModal"));
        myModal.show();
        setTimeout(function() {
            window.location.href = "./solicitar_post.php";
        }, 2000);
    });
</script>
';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./nav.css?v=1.1">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
            background-color: #A7D4FF;
        }

        .container {
            max-width: 70%;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }

        form {
            display: flex;
            gap: 50px;
        }

        form .metade {
            width: 50%;
            height: 100%;
        }

        .form-label {
            font-weight: bold;
            font-size: 1.6rem;
        }

        .form-control,
        .form-select {
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, .2) inset;
            border: 1px solid #222661;
        }


        .post-group input,
        .post-group textarea {
            border-radius: 10px;
            padding: 10px;

        }

        .post-group textarea {
            height: 200px;
        }

        .post-group textarea {
            min-height: 150px;
        }

        .btn-acao {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .extra {
            background-color: white;
            padding: 20px;
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .extra label {
            color: #0056b3;
        }

        .check-circle {
            width: 100px;
            height: 100px;
            background-color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .check-circle i {
            font-size: 50px;
            color: white;
        }

        .yeah-text {
            color: #28a745;
            font-size: 40px;
            font-weight: bold;
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <a href="./revista.php" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <div class="logo">
                <a href="./revista.php">
                    <img src="../images/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="./revista.php">
                    <img src="../images/TextoFlowUp.png" alt="Flow.UP">
                </a>
            </div>
            <?php if (!empty($_SESSION['nivel']) && $_SESSION['nivel'] == 1): ?>
                <a href="../public/index.php" class="btn btn-primary">Entrar</a>
            <?php else: ?>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php
                        if (!empty($_SESSION['foto'])) {
                            echo '<img src="' . $_SESSION['foto'] . '" class="user-profile">';
                        } else {
                            echo '<i class="bi bi-person-circle"></i>';
                        }
                        ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id']) ?>">Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>


    <div class="container mt-4">
        <h1 style="color: #1D26A8; font-weight: bolder; width: 100%; text-align: center;">CRIAÇÃO DE POST</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="metade">
                <div>
                    <div class="post-group">
                        <label style="color: #1D26A8;" for="conteudo" class="form-label">Conteúdo:</label>
                        <textarea name="conteudo[]" class="form-control" required
                            placeholder="Escreva o conteudo de sua revista..."></textarea>
                        <label style="color: #1D26A8;" for="img" class="form-label">Imagem:</label>
                        <input type="file" name="img[]" class="form-control">
                    </div>
                </div>
            </div>
            <div class="metade">
                <div class="mb-3">
                    <label style="color:#4DB742;" for="titulo" class="form-label">Título:</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label style="color:#4DB742;" for="subtitulo" class="form-label">Subtítulo:</label>
                    <input type="text" name="subtitulo" class="form-control" required>
                </div>

                <div class="mt-3">
                    <label style="color:#4DB742;" class="form-label">Tema:</label>
                    <select name="tema" class="form-select" required>
                        <option value="Física">Física</option>
                        <option value="Língua Portuguesa">Língua Portuguesa</option>
                        <option value="Língua Inglesa">Língua Inglesa</option>
                        <option value="Biologia">Biologia</option>
                        <option value="Matemática">Matemática</option>
                        <option value="Geografia">Geografia</option>
                        <option value="História">História</option>
                        <option value="Tecnologia">Tecnologia</option>
                    </select>
                </div>

                <div class="btn-acao mt-4 gap-4">
                    <button type="button" id="addPost" class="btn btn-primary">+ Adicionar Mais Conteúdo</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Enviar</button>
                </div>
            </div>
        </form>
    </div>
    <div style="display: flex; justify-content: space-around; width: 100%; margin-top: 20px;" id="post-fields">

    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal position-relative p-4">
                <div class="modal-body text-center">
                    <div class="check-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h1 class="yeah-text">Post Realizado!</h1>
                </div>
            </div>
        </div>
    </div>

    <footer style="margin-top: auto;">
        <div>
            <div class="d-flex" style="gap: 30px;">
                <a href="./revista.php">
                    <img src="../images/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="./revista.php">
                    <img src="../images/TextoFlowUp.png" alt="Flow.UP">
                </a>
            </div>
            <h4>Revista Digital criada por alunos, com o intuito de compartilhar ideias, informações e projetos inovadores. Nosso espaço é dedicado à troca de conhecimentos, com conteúdos relevantes e criativos que refletem o espírito jovem e a diversidade de perspectivas. Acompanhe e inspire-se!</h4>
        </div>
        <hr>
        <p>Copyright @2025</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        let postCount = 1;

        document.getElementById('addPost').addEventListener('click', function() {
            if (postCount < 3) {
                const newPostGroup = document.createElement('div');
                newPostGroup.classList.add('post-group', 'position-relative');
                newPostGroup.innerHTML = `
                
    <div class="extra">
            <label for="img" class="form-label">Imagem:</label>
            <input type="file" name="img[]" class="form-control">
            <label for="conteudo" class="form-label">Conteúdo:</label>
            <textarea name="conteudo[]" class="form-control" required></textarea>
            <button type="button" class="btn btn-danger position-absolute top-0 end-0" style="margin-top: -10px; margin-right: -10px;">
                <i class="bi bi-x-circle"></i>
            </button>
    </div>
        `;

                document.getElementById('post-fields').appendChild(newPostGroup);
                postCount++;

                const deleteButton = newPostGroup.querySelector('button');
                deleteButton.addEventListener('click', function() {
                    newPostGroup.remove();
                    postCount--;
                });
            } else {
                alert('Você pode adicionar no máximo 3 campos de conteúdo.');
            }
        });
    </script>
</body>

</html>