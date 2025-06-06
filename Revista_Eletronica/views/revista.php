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
    'Artes' => '#9370DB',
    'Filosofia' => '#7f8c8d',
    'Sociologia' => '#1abc9c',
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

$todos_posts_result = $mysqli->query("
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
$destaques_ids = [];
$destaques_grupos = [];
$grupos = [];
$grupos_adicionados = [];

if ($todos_posts_result && $todos_posts_result->num_rows > 0) {
    while ($pagina = $todos_posts_result->fetch_assoc()) {
        $id = $pagina['id_solicitacoes'];
        $grupo = $pagina['grupo'];

        if (count($destaques) < 3 && !in_array($grupo, $destaques_grupos)) {
            $destaques[] = $pagina;
            $destaques_ids[] = $id;
            $destaques_grupos[] = $grupo;
            continue;
        }

        if (in_array($id, $destaques_ids)) {
            continue;
        }

        if (in_array($grupo, $destaques_grupos)) {
            continue;
        }

        if (isset($grupos_adicionados[$grupo])) {
            continue;
        }

        $grupos[$grupo][] = $pagina;
        $grupos_adicionados[$grupo] = true;
    }
}





$query = "SELECT conteudo, data_expira, id_usuario_aviso, data_aviso FROM avisos ORDER BY data_aviso DESC";
$avisos = $mysqli->query($query);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./nav.css?v=1.3" rel="stylesheet">
    <link rel="stylesheet" href="./revista.css?v=1.3">
    <title>Revista Eletrônica</title>
    <style>
       .navbar {
  position: relative;
  min-height: 70px; /* Ajuste conforme altura dos elementos */
  z-index: 1000;
}

@media (min-width: 992px) {
  .navbar-collapse {
    position: absolute;
    left: 50%;
    transform: translateX(-40%) translateY(-50%);
    top: 50%;
    width: auto;
    z-index: 1020;
  }

  .perfil-wrapper {
    position: absolute;
    right: 1rem;
    top: 100%;
    transform: translateY(-50%);
    z-index: 1020;
  }

  .logo {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1020;
  }
}

    </style>
</head>

<body style="display: flex; flex-direction: column; min-height: 100vh; margin: 0;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid d-flex align-items-center justify-content-between position-relative">

            <!-- LOGO - esquerda sempre -->
            <div class="logo d-flex align-items-center">
                <a href="./revista.php" class="me-2">
                    <img src="../assets/LogoFlowUP.png" alt="Logo da Empresa Flow.UP" height="40">
                </a>
                <a href="./revista.php">
                    <img src="../assets/TextoFlowUp.png" alt="Flow.UP" height="30">
                </a>
            </div>

            <!-- PERFIL - direita sempre -->
            <div class="perfil-wrapper d-flex align-items-center">
                <?php if (!empty($_SESSION['nivel']) && $_SESSION['nivel'] == 1): ?>
                    <a href="../public/index.php" class="btn btn-primary ms-2">Entrar</a>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            if (!empty($_SESSION['foto'])) {
                                echo '<img src="' . $_SESSION['foto'] . '" class="user-profile" style="height: 5    0px; width: 50px;">';
                            } else {
                                echo '<i class="bi bi-person-circle"></i>';
                            }
                            ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id']) ?>">Perfil</a></li>
                            <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <!-- BOTÃO HAMBURGUER - mobile only -->
            <button style="left: 240px; top: 6px;" class="navbar-toggler position-absolute translate-middle-x" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                aria-expanded="false" aria-label="Toggle navigation">
                <span style="font-size: 15px;" class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- MENU CENTRAL (Desktop) e Collapse (Mobile) -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <!-- TEMAS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        TEMAS
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($cores_tema as $tema => $cor): ?>
                            <li><a class="dropdown-item" href="?tema=<?= $tema ?>"><?= $tema ?></a></li>
                        <?php endforeach; ?>
                        <li><a class="dropdown-item" href="?tema=">Todos</a></li>
                    </ul>
                </li>

                <!-- PAINEL / ADMIN -->
                <?php
                $menu_nome = ($_SESSION['nivel'] == 2) ? "PAINEL" : "ADMINISTRATIVO";
                if ($_SESSION['nivel'] > 1): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $menu_nome ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['nivel'] == 2): ?>
                                <li><a class="dropdown-item" href="./solicitar_post.php">SOLICITAR POSTAGEM</a></li>
                                <li><a class="dropdown-item" href="./revisar.php">CORRIGIR POSTAGENS</a></li>
                            <?php elseif ($_SESSION['nivel'] > 2): ?>
                                <li><a class="dropdown-item" href="./painel.php">SOLICITAÇÕES</a></li>
                                <li><a class="dropdown-item" href="./solicitar_post.php">REALIZAR POSTAGEM</a></li>
                                <li><a class="dropdown-item" href="./criar_aviso.php">ADICIONAR AVISOS</a></li>
                                <?php if ($_SESSION['nivel'] == 4): ?>
                                    <li><a class="dropdown-item" href="../adm/controle.php">PERMISSÕES</a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>






    <?php if (!empty($_SESSION['tema'])): ?>
        <div class="container mt-4 d-flex justify-content-center">
            <img class="img-fluid" src="../assets/<?= $_SESSION['tema'] ?>.png" alt="Tema selecionado">
        </div>
    <?php endif; ?>


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
                                    <h1 style="font-size:2.3rem;"><?php echo htmlspecialchars($pagina['titulo']); ?></h1>
                                    <p style="font-size:20px;"><strong><?php echo htmlspecialchars($pagina['autor'] ?? 'Autor desconhecido'); ?></strong></p>
                                    <p style="font-weight: bold; font-size:18px; text-decoration: underline;">
                                        <?php echo date('d/m/Y', strtotime($pagina['data_solicitacao'])); ?>
                                    </p>
                                    <a href="conteudo.php?id=<?= $pagina['id_solicitacoes'] ?>&destaque=Destaque," class="text-decoration-none">Ver Mais</a>
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
                <img src="../assets/Sem-Postagens.png" alt="Imagem de nenhuma postagem encontrada">
                <hr style="width:80%; border: 3px dashed #000556;">
                <h2 style="text-align: center; color: #000556;"><strong>Infelizmente, ainda não temos postagens<?= isset($_SESSION['tema']) && $_SESSION['tema'] ? ' de ' . htmlspecialchars($_SESSION['tema']) : '' ?>, mas em breve teremos.</strong></h2>
            </div>
        <?php endif; ?>
    </div>

    <div class="container mt-5 mb-5">

        <?php if (!empty($grupos)): ?>
            <h1 style="font-size:60px; color:#000556;" class="mb-5">POSTAGENS</h1>
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
    <div class="container d-flex justify-content-center flex-column mt-5 mb-5">
        <h1 style="font-size:60px; color:#000556;" class="mb-5">TEMAS</h1>
        <div class="estante">
            <div class="prateleira">
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Biologia">
                    <img class="livro img-fluid" src="../assets/livros/biologia.png" alt="Livro de Biologia">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Matemática">
                    <img class="livro img-fluid" src="../assets/livros/matematica.png" alt="Livro de Matemática">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=História">
                    <img class="livro img-fluid" src="../assets/livros/historia.png" alt="Livro de História">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Filosofia">
                    <img class="livro img-fluid" src="../assets/livros/filosofia.png" alt="Livro de Filosofia">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Língua Inglesa">
                    <img class="livro img-fluid" src="../assets/livros/ingles.png" alt="Livro de Inglês">
                </a>
            </div>

            <div class="prateleira">
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Física">
                    <img class="livro img-fluid" src="../assets/livros/fisica.png" alt="Livro de Física">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Química">
                    <img class="livro img-fluid" src="../assets/livros/quimica.png" alt="Livro de Química">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Sociologia">
                    <img class="livro img-fluid" src="../assets/livros/sociologia.png" alt="Livro de Sociologia">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Artes">
                    <img class="livro img-fluid" src="../assets/livros/artes.png" alt="Livro de Artes">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Geografia">
                    <img class="livro img-fluid" src="../assets/livros/geografia.png" alt="Livro de Geografia">
                </a>
                <a href="http://10.188.34.134:8000/eric-oliveira/Revista_Eletronica/views/revista.php?tema=Química">
                    <img class="livro img-fluid" src="../assets/livros/quimica.png" alt="Livro de Química">
                </a>
            </div>

        </div>
    </div>

    <?php if ($avisos->num_rows > 0): ?>
        <div class="container mt-4 mb-4">
            <h1 style="font-size:60px; color:#000556;" class="mb-5">MURAL DE AVISOS</h1>
            <div class="quadro">
                <?php while ($row = $avisos->fetch_assoc()): ?>
                    <div class="aviso-bloquinho">
                        <h2><strong><?= htmlspecialchars($row['data_expira'])?></strong></h2>
                        <p><?= htmlspecialchars($row['conteudo']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
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