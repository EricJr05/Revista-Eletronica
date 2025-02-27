<?php
include('./conexao.php');
include('protect_student.php');

if (!empty($_POST['titulo']) && !empty($_POST['conteudo']) && !empty($_POST['tema'])) {
    $titulo = $mysqli->real_escape_string($_POST['titulo']);
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
            $sqli_code = "INSERT INTO posts (id_usuario_solicitacoes, titulo, conteudo, tema, status, data_solicitacao, img, grupo) 
                          VALUES ('$id_usuario_solicitacoes', '$titulo', '$conteudo', '$tema', 'pendente', NOW(), '$file_name', '$novo_grupo')";
        } else {
            $sqli_code = "INSERT INTO posts (id_usuario_solicitacoes, titulo, conteudo, tema, status, data_solicitacao, grupo) 
                          VALUES ('$id_usuario_solicitacoes', '$titulo', '$conteudo', '$tema', 'pendente', NOW(), '$novo_grupo')";
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
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <div>
            <a class="navbar-brand border border-lght p-1 rounded border-2" href="./revista.php"><i class="bi bi-box-arrow-left  text-light fs-3 p-3 "></i></a>
                
            </div>
            <div class="ms-auto">

            <a class="navbar-brand" href="revista.php">
                Flow.UP
            </a>
            </div>
            <div class="ms-auto">
                <a class="btn btn-danger" href="logout.php">LOGOUT</a>
            </div>
        </div>
        </div>
    </nav>
    <div class="container mt-4">

        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
        <a href="./revista.php">Voltar</a>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" name="titulo" class="form-control" required>
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
                    <option value="FI">Física</option>
                    <option value="LP">Língua Portuguesa</option>
                    <option value="IN">Língua Inglesa</option>
                    <option value="BIO">Biologia</option>
                    <option value="MA">Matemática</option>
                    <option value="GEO">Geografia</option>
                    <option value="HI">História</option>
                    <option value="TECNOLOGIA">Tecnologia</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Enviar Solicitação</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById("addPost").addEventListener("click", function() {
            let newFields = document.createElement("div");
            newFields.classList.add("post-group", "mt-3");
            newFields.innerHTML = `
                <label for="img" class="form-label">Imagem:</label>
                <input type="file" name="img[]" class="form-control">
                <label for="conteudo" class="form-label">Conteúdo:</label>
                <textarea name="conteudo[]" class="form-control" required></textarea>
            `;
            document.getElementById("post-fields").appendChild(newFields);
        });
    </script>
</body>

</html>