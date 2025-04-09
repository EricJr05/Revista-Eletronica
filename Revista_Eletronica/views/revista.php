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



// Consulta única: já busca todos os aprovados com total de likes
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


// Arrays para separar destaques e grupos
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
    <link rel="stylesheet" href="./nav.css">
    <title>Revista Eletrônica</title>
    <style>
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            width: 100%;
            background: #eeeeee;
            height: 50vh;
            overflow: hidden;
            border: 3px solid transparent;
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
            background-color:rgb(4, 0, 255);
            padding: 12px;
            border-radius: 40px;    
        }
        .header-container>div a:hover {
            background-color:rgb(0, 68, 255); 
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
                            <li><a class="dropdown-item" href="./perfil.php">Perfil</a></li>
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
                                        <strong><?php echo htmlspecialchars($pagina['tema']); ?></strong>
                                    </h4>
                                    <h1 style="font-size:60px;"><?php echo htmlspecialchars($pagina['titulo']); ?></h1>
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
            <h1 class="text-center">Nenhum post em destaque</h1>
        <?php endif; ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>