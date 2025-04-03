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
        $file_name = $_FILES['img']['name'][$index];
        $tempname = $_FILES['img']['tmp_name'][$index];
        $folder = 'images/' . $file_name;

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
        }
    }
    echo 'Solicitação Enviada';
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
    <link rel="stylesheet" href="./nav.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 800px;
            margin-top: 50px;
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

        .form-label {
            font-weight: bold;
        }

        .form-control,
        .form-select {
            border-radius: 5px;
        }

        .post-group {
            margin-bottom: 30px;
        }

        .post-group label {
            font-size: 1rem;
        }

        .post-group input,
        .post-group textarea {
            border-radius: 5px;
            padding: 10px;
        }

        .post-group textarea {
            min-height: 150px;
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
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        if (!empty($_SESSION['foto'])) {
                            echo '<img src="' . $_SESSION['foto'] . '" class="user-profile">';
                        } else {
                            echo '<i class="bi bi-person-circle"></i>';
                        }
                        ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li><a class="dropdown-item" href="./perfil.php">Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>


    <div class="container mt-4">
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="subtitulo" class="form-label">Subtítulo:</label>
                <input type="text" name="subtitulo" class="form-control" required>
            </div>

            <div id="post-fields">
                <div class="post-group">
                    <label for="img" class="form-label">Imagem:</label>
                    <input type="file" name="img[]" class="form-control">
                    <label for="conteudo" class="form-label">Conteúdo:</label>
                    <textarea name="conteudo[]" class="form-control" required></textarea>
                </div>
            </div>

            <button type="button" id="addPost" class="btn btn-secondary mt-2">Adicionar Mais Conteúdo</button>

            <div class="mt-3">
                <label class="form-label">Tema:</label>
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

            <div class="mt-4 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let postCount = 1;

        document.getElementById('addPost').addEventListener('click', function() {
            if (postCount < 3) {
                const newPostGroup = document.createElement('div');
                newPostGroup.classList.add('post-group', 'position-relative');
                newPostGroup.innerHTML = `
            <label for="img" class="form-label">Imagem:</label>
            <input type="file" name="img[]" class="form-control">
            <label for="conteudo" class="form-label">Conteúdo:</label>
            <textarea name="conteudo[]" class="form-control" required></textarea>
            <button type="button" class="btn btn-danger position-absolute top-0 end-0" style="margin-top: -10px; margin-right: -10px;">
                <i class="bi bi-x-circle"></i>
            </button>
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