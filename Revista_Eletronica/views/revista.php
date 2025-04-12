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


if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['nivel'])) {
    $_SESSION['nivel'] = 1;
}



if (isset($_GET['tema'])) {
    if ($_GET['tema'] === '') {
        unset($_SESSION['tema']);
    } else {
        $_SESSION['tema'] = $_GET['tema'];
    }
}

$where_tema = isset($_SESSION['tema']) ? "AND p.tema = '" . $mysqli->real_escape_string($_SESSION['tema']) . "'" : "";

$destaques_result = $mysqli->query("
    SELECT p.*, COUNT(l.id_post_like) AS total_likes
    FROM posts p
    LEFT JOIN likes l ON p.id_solicitacoes = l.id_post_like
    WHERE p.status = 'aprovado' $where_tema
    GROUP BY p.id_solicitacoes
    ORDER BY total_likes DESC
    LIMIT 3
");



$query = $mysqli->query("
    SELECT 
        p.*, 
        u.nome AS autor, 
        (SELECT COUNT(*) FROM likes l WHERE l.id_post_like = p.id_solicitacoes) AS total_likes
    FROM posts p
    INNER JOIN usuarios u ON p.id_usuario_solicitacoes = u.id
    WHERE p.status = 'aprovado' $where_tema
    ORDER BY total_likes DESC, p.data_solicitacao DESC
");


$destaques = [];
$grupos = [];

if ($query && $query->num_rows > 0) {
    $index = 0;
    while ($pagina = $query->fetch_assoc()) {
        if ($index < 3) {
            $destaques[] = $pagina;
        } else {
            $grupos[$pagina['grupo']][] = $pagina;
        }
        $index++;
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
    <link href="./nav.css" rel="stylesheet">
    <title>Revista Eletrônica</title>
    <style>
        body {
            background-color: rgb(174, 215, 253);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            width: 100%;
            background: white;
            height: 50vh;
            overflow: hidden;
            border: 4px solid transparent;
            border-image: linear-gradient(to left, green, blue) 1;
            box-shadow: 0 3px 14px rgba(0, 0, 0, .4);
        }

        .header-container>div {
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 3px;
            padding: 10px;
            text-align: center;
        }

        .header-container>div img {
            width: auto;
            height: 100%;
            object-fit: contain;
        }

        .header-container>div a {
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            color: white;
            background-color: rgb(4, 0, 255);
            padding: 12px;
            border-radius: 40px;
        }

        .header-container>div a:hover {
            background-color: rgb(0, 68, 255);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(100%);
        }

        .masonry-container {
            column-count: 3;
            column-gap: 1rem;
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
        }

        .sem-postagens {
            width: 100%;
            height: 300px;
            background-color: white;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .sem-postagens img {
            height: 60%;
            width: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <div class="logo">
                    <a href="./revista.php">
                        <img src="../images/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                    </a>
                    <a href="./revista.php">
                        <img src="../images/TextoFlowUp.png" alt="Flow.UP">
                    </a>
                </div>

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">TEMAS</a>
                        <ul class="dropdown-menu">
                            <?php
                            foreach ($cores_tema as $tema => $cor) {
                                echo "<li><a class='dropdown-item' href='?tema=$tema'>$tema</a></li>";
                            }
                            ?>
                            <li><a class="dropdown-item" href="?tema=">Todos</a></li>
                        </ul>
                    </li>

                    <?php
                    $menu_nome = ($_SESSION['nivel'] == 2) ? "PAINEL" : "ADMINISTRATIVO";
                    if ($_SESSION['nivel'] > 1) {
                        echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $menu_nome . '</a>
                            <ul class="dropdown-menu">';

                        if ($_SESSION['nivel'] == 2) {
                            echo '<li><a class="dropdown-item" href="./solicitar_post.php">SOLICITAR POST</a></li>
                                  <li><a class="dropdown-item" href="./revisar.php">REFAZER POST</a></li>';
                        } elseif ($_SESSION['nivel'] > 2) {
                            echo '<li><a class="dropdown-item" href="./painel.php">SOLICITAÇÕES</a></li>';
                            if ($_SESSION['nivel'] == 4) {
                                echo '<li><a class="dropdown-item" href="../adm/controle.php">CONTROLE DE PERMISSÕES</a></li>';
                            }
                        }
                        echo '</ul></li>';
                    }
                    ?>
                </ul>

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
        </div>
    </nav>


    <div class="container mt-4">
        <?php if (!empty($destaques)): ?>
            <div id="destaqueCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <?php
                    $active = true;
                    foreach ($destaques as $pagina):
                        $cor_tema = $cores_tema[$pagina['tema']] ?? '#000';
                    ?>
                        <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                            <div class="header-container">
                                <div>
                                    <h4 style="font-size:30px; color:<?php echo htmlspecialchars($cor_tema); ?>;">
                                        <strong>Destaque, <?php echo htmlspecialchars($pagina['tema']); ?></strong>
                                    </h4>
                                    <h1 style="font-size:50px;"><?php echo htmlspecialchars($pagina['titulo']); ?></h1>
                                    <p style="font-size:20px;"><strong><?php echo htmlspecialchars($pagina['autor'] ?? 'Autor desconhecido'); ?></strong></p>
                                    <p style="font-weight: bold; font-size:18px; text-decoration: underline;">
                                        <?php echo date('d/m/Y', strtotime($pagina['data_solicitacao'])); ?>
                                    </p>
                                    <a href="conteudo.php?id=<?= $pagina['id_solicitacoes'] ?>" class="text-decoration-none">Ver Mais</a>
                                </div>
                                <?php if (!empty($pagina['img'])): ?>
                                    <div>
                                        <img src="../images/<?php echo htmlspecialchars($pagina['img']); ?>" alt="Imagem do post" class="img-fluid">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                        $active = false;
                    endforeach;
                    ?>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#destaqueCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#destaqueCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        <?php else: ?>
            <div class="sem-postagens">
                <img src="../images/Sem-Postagens.png" alt="Imagem de nenhuma postagem encontrada">
                <hr style="width:80%; border: 3px dashed #000556;">
                <h2 style="text-align: center; color: #000556;"><strong>infelizmente, ainda não temos postagens, mas em breve teremos.</strong></h2>
            </div>
        <?php endif; ?>
    </div>

    <div class="container mt-5 mb-5">

        <?php if (!empty($grupos)): ?>
            <h1 style="font-size:65px; color:#000556;" class="mb-5">POSTAGENS</h1>
            <div class="masonry-container">
                <?php foreach ($grupos as $grupo => $postagens): ?>
                    <?php
                    $post = $postagens[0];
                    $cor_tema = $cores_tema[$post['tema']] ?? '#000';
                    ?>
                    <div class="masonry-item">
                        <a href="conteudo.php?id=<?= $post['id_solicitacoes'] ?>" style="text-decoration: none;">
                            <div class="card border-0 shadow-sm mb-3">
                                <?php if (!empty($post['img'])): ?>
                                    <img src="../images/<?php echo htmlspecialchars($post['img']); ?>"
                                        class="card-img-top"
                                        alt="Imagem do post"
                                        style="height: 200px; object-fit: contain; background-color: #f8f9fa;">
                                <?php endif; ?>
                                <div class="card-body py-2">
                                    <h5 class="card-title mb-1" style="font-size: 1.2rem;">
                                        <?php echo htmlspecialchars($post['titulo']); ?>
                                    </h5>
                                    <p class="card-text text-muted mb-2" style="font-size: 0.95rem;">
                                        <?php echo htmlspecialchars($post['subtitulo']); ?>
                                    </p>
                                    <?php if (!empty($post['tema'])): ?>
                                        <p class="mb-0" style="color: <?= htmlspecialchars($cor_tema) ?>;">
                                            <strong><?= htmlspecialchars($post['tema']) ?></strong>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>