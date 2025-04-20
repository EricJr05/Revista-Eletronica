<?php
include('../config/conexao.php');

$cores_tema = [
    'Educação Física' => '#e67e22',
    'Física' => '#f1c40f',
    'Química' => '#8e44ad',
    'Biologia' => '#2ecc71',
    'Matemática' => '#2980b9',
    'Língua Portuguesa' => '#e74c3c',
    'Língua Inglesa' => '#34495e',
    'Geografia' => '#16a085',
    'História' => '#d35400',
    'Tecnologia' => '#3498db',
];

session_start();
$id_user = $_SESSION['id'] ?? null;

$ref = $_GET['ref'] ?? null;
$destaque = $_GET['destaque'] ?? null;

if (!isset($_GET['id'])) {
    die("Postagem não encontrada.");
}

$post_id = $mysqli->real_escape_string($_GET['id']);
$result = $mysqli->query("
    SELECT posts.*, usuarios.nome AS autor, usuarios.perfil_foto AS foto_autor, usuarios.id AS id_autor
    FROM posts 
    JOIN usuarios ON posts.id_usuario_solicitacoes = usuarios.id 
    WHERE posts.id_solicitacoes = '$post_id'
");

if ($result->num_rows == 0) {
    die("Postagem não encontrada.");
}

$pagina = $result->fetch_assoc();
$grupo = $pagina['grupo'];
$tema = $pagina['tema'];
$cor_tema = $cores_tema[$tema] ?? '#ccc';

$grupo_result = $mysqli->query("SELECT * FROM posts WHERE grupo = '$grupo' ORDER BY data_solicitacao ASC");
$posts_grupo = [];
while ($post = $grupo_result->fetch_assoc()) {
    $posts_grupo[] = $post;
}

$likes_result = $mysqli->query("
    SELECT 
        (SELECT COUNT(*) FROM likes WHERE id_post_like = '$post_id') AS total_likes,
        (SELECT COUNT(*) FROM likes WHERE id_post_like = '$post_id' AND id_usuario_like = '$id_user') AS curtiu
");

$likes = $likes_result->fetch_assoc();

$comentarios_result = $mysqli->query("
SELECT comentarios.*, usuarios.nome, usuarios.perfil_foto 
FROM comentarios 
INNER JOIN usuarios ON comentarios.user_id = usuarios.id 
WHERE comentarios.post_id = '$post_id' 
ORDER BY comentarios.id DESC;
");

$comentarios = [];
while ($coment = $comentarios_result->fetch_assoc()) {
    $comentarios[] = $coment;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./nav.css?v=1.3">
    <title><?php echo htmlspecialchars($pagina['titulo']); ?></title>
    <style>

        body{
            justify-content: start;
        }
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            width: 100%;
            background: #eeeeee;
            height: 48vh;
            overflow: hidden;
            border-bottom: 6px solid transparent;
            border-image: linear-gradient(to left, green, blue) 1;
            box-shadow: 0 3px 14px rgba(0, 0, 0, .4);
        }

        .header-container div {
            width: 40%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 3px;
        }

        .header-container div:nth-of-type(1) {
            width: 60%;
        }

        .header-container div img {
            width: auto;
            height: 100%;
        }

        .content-section {
            padding: 20px;
        }

        .content-section h1 {
            font-size: 38px;
        }

        .content-section p {
            font-size: 20px;
            line-height: 1.5;
        }

        .post-bloco {
            margin-bottom: 40px;
            overflow: auto;
            position: relative;
        }

        .imagem-post {
            float: right;
            margin-left: 20px;
            max-width: 250px;
        }

        .imagem-post img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .texto-post {
            text-align: justify;
        }


        .container_autor {
            width: 100%;
            height: 80px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 2px solid black;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 40px;
            gap: 10px;
        }

        .user-profile {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .container_autor .like {
            text-decoration: none;
            color: black;
            font-size: 40px;
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .container_autor .like i {
            transition: all .1s linear;
        }

        .container_autor .like i:hover {
            font-size: 50px;
        }

        .container_autor .user-profile {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form_comentario {
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form_comentario .user-profile {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form_comentario i.bi-person-circle {
            font-size: 65px;
            color: black;
        }

        .form_comentario textarea {
            width: 75%;
            height: 80px;
            border: 3px solid black;
            border-radius: 30px;
            padding: 10px;
            resize: none;
            font-size: 16px;
        }

        .form_comentario button {
            width: 15%;
            height: 60px;
            border-radius: 40px;
            border: none;
            background: black;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container_comentarios {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            width: 100%;
            margin: 14px auto;
            background-color: #f9f9f9;
            border-radius: 10px;
            transition: all .1s ease;
            margin-bottom: 40px;
        }

        .container_comentarios:hover {
            transform: scale(1.05);
        }

        .container_comentarios img.user-profile {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .container_comentarios i.bi-person-circle {
            font-size: 60px;
            color: #aaa;
        }

        .container_comentarios>div:last-child {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .container_comentarios p {
            margin: 0;
            padding: 2px 0;
            word-break: break-word;
        }
        
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <a
                <?php if ($ref === 'painel'): ?>
                href="./painel.php"
                <?php else: ?>
                href="./revista.php"
                <?php endif; ?>

                class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <div class="logo">
                <a href="./revista.php">
                    <img src="../assets/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="./revista.php">
                    <img src="../assets/TextoFlowUp.png" alt="Flow.UP">
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
                    <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id'])?>">Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="header-container">
        <div>
            <h4 style="font-size:30px; color:<?php echo htmlspecialchars($cor_tema); ?>;"><strong><?= htmlspecialchars($destaque)?> <?php echo htmlspecialchars($pagina['tema']); ?></strong></h4>
            <h1 style="font-size:60px; text-align: center;"> <?= htmlspecialchars($pagina['titulo']); ?></h1>
            <p style="font-size:20px;"><strong><?php echo htmlspecialchars($pagina['autor']); ?></strong></p>
            <p style="font-weight: bold; font-size:18px; text-decoration: underline;"><?php echo date('d/m/Y', strtotime($pagina['data_solicitacao'])); ?></p>
        </div>
        <?php if (!empty($pagina['img'])): ?>
            <div>
                <img src="../images/<?php echo htmlspecialchars($pagina['img']); ?>" alt="Imagem do post" class="img-fluid">
            </div>
        <?php endif; ?>
    </div>

    <div class="container mt-5">
        <div class="content-section">
            <h1><strong><?php echo htmlspecialchars($pagina['subtitulo']); ?></strong></h1>
            <?php foreach ($posts_grupo as $index => $post): ?>
                <div class="post-bloco">
                    <?php if (!empty($post['img'])): ?>
                        <div class="imagem-post">
                            <img src="../images/<?php echo htmlspecialchars($post['img']); ?>" alt="Imagem do post">
                        </div>
                    <?php endif; ?>
                    <div class="texto-post">
                        <p><?php echo nl2br(htmlspecialchars($post['conteudo'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



        <div class="container_autor mb-5">
            <?php
            if (!empty($pagina['foto_autor'])) {
                echo '<a href=./perfil.php?id=' . htmlspecialchars($pagina['id_autor']) .  '>';
                echo '<img src="' . htmlspecialchars($pagina['foto_autor']) . '" class="user-profile">';
                echo '</a>';
            } else {
                echo '<a style="text-decoration: none; color: black; font-size: 65px;" href=./perfil.php?id=' . htmlspecialchars($pagina['id_autor']) .  '>';
                echo '<i class="bi bi-person-circle"></i>';
                echo '</a>';
            }
            ?>
            <h3><strong>Escritor: <?php echo htmlspecialchars($pagina['autor']); ?></strong></h3>
            <h4>Data: <?php echo date('d/m/Y', strtotime($pagina['data_solicitacao'])); ?></h4>
            <?php if ($pagina['status'] == 'aprovado'): ?>
                <?php if ($id_user): ?>
                    <?php if ($likes['curtiu'] > 0): ?>
                        <a class="like" href="../public/likes.php?ref=<?= $post_id ?>"><i style="color: red;" class="bi bi-arrow-through-heart-fill"></i> <?php echo $likes['total_likes']; ?></a>
                    <?php else: ?>
                        <a class="like" href="../public/likes.php?ref=<?= $post_id ?>"><i class="bi bi-heart"></i> <?php echo $likes['total_likes']; ?></a>
                    <?php endif; ?>
                <?php else: ?>
                    <p><a href="../public/index.php">Faça login para curtir</a></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>


        <?php if ($pagina['status'] == 'aprovado'): ?>
            <hr style="border: black 2px solid; opacity:1; border-radius:30px;">

            <h1><strong>Comentários <i class="bi bi-chat"></i></strong></h1>

            <?php if ($id_user): ?>
                <form class="form_comentario" action="../public/comentarios.php" method="POST">
                    <input type="hidden" name="id_post_like" value="<?= $post_id ?>">
                    <?php
                    if (!empty($_SESSION['foto'])) {
                        echo '<img src="' . $_SESSION['foto'] . '" class="user-profile">';
                    } else {
                        echo '<i class="bi bi-person-circle"></i>';
                    }
                    ?>
                    <textarea name="comentario" required placeholder="Digite seu comentário..." class="form-control"></textarea>
                    <button type="submit" class="btn btn-success mt-2">Enviar Comentário</button>
                </form>
            <?php else: ?>
                <p><a href="../public/index.php">Faça login para comentar</a></p>
            <?php endif; ?>

            <hr style="border: black 2px solid; opacity:1; border-radius:30px;">


            <?php if (count($comentarios) > 0): ?>
                <?php foreach ($comentarios as $coment): ?>
                    <div class="container_comentarios">
                        <div>
                            <?php
                            if (!empty($coment['perfil_foto'])) {
                                echo '<img src="' . $coment['perfil_foto'] . '" class="user-profile">';
                            } else {
                                echo '<i class="bi bi-person-circle"></i>';
                            }
                            ?>
                        </div>
                        <div>
                            <p><strong><?php echo htmlspecialchars($coment['nome']); ?></strong></p>
                            <p><?php echo htmlspecialchars($coment['conteudo']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sem comentários ainda.</p>
            <?php endif; ?>
        <?php endif; ?>

    </div>

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