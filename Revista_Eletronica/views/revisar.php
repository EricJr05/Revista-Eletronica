<?php
include('../middleware/protect_student.php');
include('../config/conexao.php');

$result = $mysqli->query("SELECT * FROM posts WHERE id_usuario_solicitacoes = " . (int)$_SESSION['id'] . " AND `status` = 'revisar'");

$grupos = [];

if ($result && $result->num_rows > 0) {
    while ($pagina = $result->fetch_assoc()) {
        $grupos[$pagina['grupo']][] = $pagina;
    }
}

if (!empty($_POST['titulo']) && !empty($_POST['conteudo']) && !empty($_POST['tema'])) {
    $titulo = $mysqli->real_escape_string($_POST['titulo']);
    $tema = $mysqli->real_escape_string($_POST['tema']);
    $id_usuario_solicitacoes = $_SESSION['id'];

    foreach ($_POST['conteudo'] as $index => $conteudo) {
        $id_solicitacao = $_POST['id'][$index];
        $conteudo = $mysqli->real_escape_string($conteudo);
        $file_name = $_FILES['img']['name'][$index];
        $tempname = $_FILES['img']['tmp_name'][$index];
        $folder = 'images/' . $file_name;

        if (move_uploaded_file($tempname, $folder)) {
            $sqli_code = "UPDATE posts SET 
                            titulo = '$titulo', 
                            conteudo = '$conteudo', 
                            tema = '$tema', 
                            status = 'pendente', 
                            data_solicitacao = NOW(), 
                            img = '$file_name' 
                          WHERE id_solicitacoes = " . $id_solicitacao;
        } else {
            $sqli_code = "UPDATE posts SET 
                            titulo = '$titulo', 
                            conteudo = '$conteudo', 
                            tema = '$tema', 
                            status = 'pendente', 
                            data_solicitacao = NOW() 
                          WHERE id_solicitacoes = " . $id_solicitacao;
        }

        $sqli_query = $mysqli->query($sqli_code);
        if (!$sqli_query) {
            echo 'Erro ao Enviar: ' . $mysqli->error;
        }
    }
    header('location: ./revisar.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./nav.css?v=1.0">
    <title>Revisar Post</title>
</head>

<style>
    
</style>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow" data-bs-theme="dark">
        <div class="container-fluid">
            <div>
                <a href="./revista.php" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="logo">
                <a href="./revista.php">
                    <img src="../images/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="./revista.php">
                    <img src="../images/TextoFlowUp.png" alt="Flow.UP">
                </a>
            </div>
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
                <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id'])?>">Perfil</a></li>
                    <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (!empty($grupos)): ?>
        <?php foreach ($grupos as $grupo => $posts): ?>
            <form method="POST" enctype="multipart/form-data">
                <h1><?php echo "Coletânia $grupo"; ?></h1>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" name="titulo" class="form-control" required value='<?php echo $posts[0]['titulo']; ?>'>
                </div>
                <?php foreach ($posts as $post): ?>
                    <div id="post-fields">
                        <div class="post-group">
                            <label for="img" class="form-label">Imagem:</label>
                            <input type="file" name="img[]" class="form-control">
                            <?php if (!empty($post['img'])): ?>
                                <img src="images/<?php echo htmlspecialchars($post['img']); ?>" class="img-fluid" style="max-width: 100%; max-height: 300px; height: 30vh;">
                            <?php endif; ?>
                            <label for="conteudo" class="form-label">Conteúdo:</label>
                            <textarea name="conteudo[]" class="form-control" required><?php echo $post['conteudo']; ?></textarea>
                        </div>
                    </div>
                    <input type="text" name='id[]' value="<?php echo $post['id_solicitacoes'] ?>" hidden>
                <?php endforeach; ?>


                <div class="mt-3">
                    <label class="form-label">Tema:</label>
                    <select name="tema" class="form-select" required>
                        <option value="FI" <?php if ($post['tema'] == 'FI') echo 'selected'; ?>>Física</option>
                        <option value="LP" <?php if ($post['tema'] == 'LP') echo 'selected'; ?>>Língua Portuguesa</option>
                        <option value="IN" <?php if ($post['tema'] == 'IN') echo 'selected'; ?>>Língua Inglesa</option>
                        <option value="BIO" <?php if ($post['tema'] == 'BIO') echo 'selected'; ?>>Biologia</option>
                        <option value="MA" <?php if ($post['tema'] == 'MA') echo 'selected'; ?>>Matemática</option>
                        <option value="GEO" <?php if ($post['tema'] == 'GEO') echo 'selected'; ?>>Geografia</option>
                        <option value="HI" <?php if ($post['tema'] == 'HI') echo 'selected'; ?>>História</option>
                        <option value="TECNOLOGIA" <?php if ($post['tema'] == 'TECNOLOGIA') echo 'selected'; ?>>Tecnologia</option>
                    </select>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Enviar Revisão</button>
                </div>
            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <h1>NENHUMA REVISÃO ENCONTRADA</h1>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>