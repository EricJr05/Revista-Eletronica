<?php
include('conexao.php');

$cores_tema = [
    'ED' => '#e67e22',
    'FI' => '#f1c40f',
    'QUI' => '#8e44ad',
    'BIO' => '#2ecc71',
    'MA' => '#2980b9',
    'LP' => '#e74c3c',
    'IN' => '#34495e',
    'GEO' => '#16a085',
    'HI' => '#d35400',
    'TECNOLOGIA' => '#3498db',
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

$where_tema = isset($_SESSION['tema']) ? "AND tema = '" . $mysqli->real_escape_string($_SESSION['tema']) . "'" : "";



$result = $mysqli->query("SELECT * FROM posts WHERE status = 'aprovado' $where_tema ORDER BY data_solicitacao DESC");

$grupos = [];

if ($result && $result->num_rows > 0) {
    while ($pagina = $result->fetch_assoc()) {
        $grupos[$pagina['grupo']][] = $pagina;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Revista Eletrônica</title>
    <style>
        .carousel-container {
            max-height: 98vh;
        }

        @media (max-width: 768px) {
            .carousel-container {
                max-height: 60vh;
            }

            img {
                max-height: 250px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.2rem;
            }

            .carousel-inner {
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="revista.php">
                Flow.UP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <?php
                    if ($_SESSION['nivel'] == 2) {
                        echo '<li class="nav-item"><a class="nav-link" href="solicitar_post.php">SOLICITAR-POST</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="revisar.php">REFAZER-POST</a></li>';
                    }
                    if ($_SESSION['nivel'] > 2) {
                        echo '<li class="nav-item"><a class="nav-link" href="painel.php">PAINEL</a></li>';
                    }
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            TEMAS
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            foreach ($cores_tema as $tema => $cor) {
                                echo "<li><a class='dropdown-item' href='?tema=$tema'>$tema</a></li>";
                            }
                            ?>
                            <li><a class="dropdown-item" href="?tema=">Todos</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="ms-auto">
                    <?php
                    if ($_SESSION['nivel'] == 1) {
                        echo '<a class="btn btn-primary" href="logout.php">ENTRAR</a>';
                    }

                    if ($_SESSION['nivel'] > 1):


                        echo '<a class="btn btn-danger" href="logout.php">LOGOUT</a>';
                    endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>
            <?php
            echo isset($_SESSION['tema']) && $_SESSION['tema'] ? "Filtrando por: " . htmlspecialchars($_SESSION['tema']) : "Exibindo todos os temas";
            ?>
        </h3>

        <?php if (!empty($grupos)): ?>
            <div id="revistaCarousel" class="carousel slide carousel-container" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $active = true;
                    foreach ($grupos as $grupo):
                        $cor = $cores_tema[$grupo[0]['tema']] ?? $cores_tema['Outros'];
                    ?>
                        <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                            <div class="p-4" style="background-color: <?php echo htmlspecialchars($cor); ?>; border-radius: 10px;">
                                <h1 class="text-white text-center"><?php echo htmlspecialchars($grupo[0]['titulo']); ?></h1>

                                <?php foreach ($grupo as $pagina): ?>
                                    <div class="text-center">
                                        <?php if (!empty($pagina['img'])): ?>
                                            <img src="images/<?php echo htmlspecialchars($pagina['img']); ?>" class="img-fluid" style="max-width: 100%; max-height: 300px; height: 30vh;">
                                        <?php endif; ?>
                                        <p class="text-white"><?php echo nl2br(htmlspecialchars($pagina['conteudo'])); ?></p>
                                    </div>
                                <?php endforeach; ?>

                                <p class="text-white text-center"><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($grupo[0]['data_solicitacao'])); ?></p>
                            </div>
                        </div>
                    <?php
                        $active = false;
                    endforeach;
                    ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#revistaCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#revistaCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        <?php else: ?>
            <h1>NENHUMA PÁGINA ENCONTRADA</h1>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>