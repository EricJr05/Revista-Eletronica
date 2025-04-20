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
    <link rel="stylesheet" href="./nav.css?v=1.3">
    <title>Revisar Post</title>
</head>

<style>
body {background-color: #A7D4FF; }
@media (max-width:768px){
    h1{
        font-size: 24px !important;
    }
}

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
                    <img src="../assets/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="./revista.php">
                    <img src="../assets/TextoFlowUp.png" alt="Flow.UP">
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
                    <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id']) ?>">Perfil</a></li>
                    <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (!empty($grupos)): ?>
    <div class="container py-5">
        <?php foreach ($grupos as $grupo => $posts): ?>
            <form method="POST" enctype="multipart/form-data" class="mb-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><?php echo "Coletânia $grupo"; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título:</label>
                            <input type="text" name="titulo" class="form-control" required value='<?php echo $posts[0]['titulo']; ?>'>
                        </div>

                        <?php foreach ($posts as $post): ?>
                            <div class="border rounded p-3 mb-4 bg-light">
                                <div class="mb-3">
                                    <label for="img" class="form-label">Imagem:</label>
                                    <input type="file" name="img[]" class="form-control">
                                    <?php if (!empty($post['img'])): ?>
                                        <div class="mt-2 text-center">
                                            <img src="../images/<?php echo htmlspecialchars($post['img']); ?>" class="img-fluid rounded" style="max-width: 100%; max-height: 300px; height: 30vh;">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="conteudo" class="form-label">Conteúdo:</label>
                                    <textarea name="conteudo[]" class="form-control" rows="5" required><?php echo $post['conteudo']; ?></textarea>
                                </div>

                                <input type="text" name="id[]" value="<?php echo $post['id_solicitacoes'] ?>" hidden>
                            </div>
                        <?php endforeach; ?>

                        <div class="mb-3">
                            <label class="form-label">Tema:</label>
                            <select name="tema" class="form-select" required>
                                <option value="Física" <?php if ($post['tema'] == 'Física') echo 'selected'; ?>>Física</option>
                                <option value="Língua Portuguesa" <?php if ($post['tema'] == 'Língua Portuguesa') echo 'selected'; ?>>Língua Portuguesa</option>
                                <option value="Língua Inglesa" <?php if ($post['tema'] == 'Língua Inglesa') echo 'selected'; ?>>Língua Inglesa</option>
                                <option value="Biologia" <?php if ($post['tema'] == 'Biologia') echo 'selected'; ?>>Biologia</option>
                                <option value="Matemática" <?php if ($post['tema'] == 'Matemática') echo 'selected'; ?>>Matemática</option>
                                <option value="Geografia" <?php if ($post['tema'] == 'Geografia') echo 'selected'; ?>>Geografia</option>
                                <option value="História" <?php if ($post['tema'] == 'História') echo 'selected'; ?>>História</option>
                                <option value="Artes" <?php if ($post['tema'] == 'Artes') echo 'selected'; ?>>Artes</option>
                                <option value="Educação Física" <?php if ($post['tema'] == 'Educação Física') echo 'selected'; ?>>Educação Física</option>
                                <option value="Química" <?php if ($post['tema'] == 'Química') echo 'selected'; ?>>Química</option>
                                <option value="Filosofia" <?php if ($post['tema'] == 'Filosofia') echo 'selected'; ?>>Filosofia</option>
                                <option value="Sociologia" <?php if ($post['tema'] == 'Sociologia') echo 'selected'; ?>>Sociologia</option>
                                <option value="Tecnologia" <?php if ($post['tema'] == 'Tecnologia') echo 'selected'; ?>>Tecnologia</option>
                            </select>
                        </div>

                            <button type="submit" class="btn btn-success mt-3 w-100">Enviar Revisão</button>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="container text-center py-5">
    <h1 style="font-size:60px; color:#000556;" class="mb-5">NENHUMA CORREÇÃO ENCONTRADA</h1>
    </div>
<?php endif; ?>


    <footer style="margin-top: auto;">
        <div>
            <div class="d-flex" style="gap: 30px;">
                <a href="./revista.php">
                    <img src="../assets/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="./revista.php">
                    <img src="../assets/TextoFlowUp.png" alt="Flow.UP">
                </a>
            </div>
            <h4>Revista Digital criada por alunos, com o intuito de compartilhar ideias, informações e projetos inovadores. Nosso espaço é dedicado à troca de conhecimentos, com conteúdos relevantes e criativos que refletem o espírito jovem e a diversidade de perspectivas. Acompanhe e inspire-se!</h4>
        </div>
        <hr>
        <p>Copyright @2025</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>