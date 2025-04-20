<?php
include('../config/conexao.php');
include('../middleware/protect.php');

$id_permissao = $_SESSION['nivel'];
$permissoes = $mysqli->query("SELECT p.* FROM usuarios u 
                              JOIN permissoes p ON u.id_permissoes_usuario = p.id 
                              WHERE p.id = $id_permissao");

if (isset($_POST['acao']) && isset($_POST['grupo'])) {
    $grupo = intval($_POST['grupo']);
    $acao = $_POST['acao'];

    if (in_array($acao, ['aprovado', 'rejeitado', 'revisar'])) {
        $sqli_code = "UPDATE posts SET status = '$acao', data_solicitacao = NOW() WHERE grupo = $grupo";
        $sqli_query = $mysqli->query($sqli_code);
    }
}

$result = $mysqli->query("SELECT post.*, user.nome 
                          FROM posts post
                          JOIN usuarios user ON post.id_usuario_solicitacoes = user.id 
                          WHERE post.status = 'pendente' 
                          ORDER BY post.grupo, post.data_solicitacao");

$grupos = [];
while ($solicitacao = $result->fetch_assoc()) {
    $grupos[$solicitacao['grupo']][] = $solicitacao;
}

$total_grupos_pendentes = count($grupos);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./nav.css?v=1.3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Painel</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
            background-color: #A7D4FF;
        }

        img {
            width: 200px;
            height: auto;
        }

        .grupo-container {
            box-shadow: 2px 2px 5px rgba(0, 0, 0, .6);
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 20px;
            width: 65%;
            display: flex;
            justify-content: space-between;
        }


        main {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
            width: 70%;
            align-items: end;
        }

        form button {
            border: none;
            width: 100%;
            padding: 10px;
            color: white;
            font-weight: bolder;
            font-size: 20px;
            box-shadow: -1px -1px 10px rgba(0, 0, 0, .4);
            transition: transform .1s ease-in;
        }

        form button:hover {
            transform: scale(1.02);
        }

        .post {
            width: 25%;
            background: url('../assets/paisagem-de-montanha.avif');
            border-radius: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: bolder;
            color: white;
            font-size: 20px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            gap: 8px;
            transition: transform .1s ease-in;
            cursor: pointer;
            text-decoration: none;

        }

        .post:hover {
            transform: scale(1.02);
        }

        .post p {
            margin: 0;
            padding: 0;
        }

        @media (max-width:768px) {
            .grupo-container{
                width: 95%;
            }

            h2{
                width: 95% !important;
            }
            h1{
                font-size: 30px;
                text-align: center;
            }
            .grupo-container a{
                width: 50%;
            }
            .grupo-container button{
                width: 90%;
            }
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
                    <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id'])?>">Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <?php if ($permissoes): ?>
            <?php $permissoes = $permissoes->fetch_assoc(); ?>

            <?php if ($permissoes && $permissoes['visionar_post'] == 'S'): ?>

                <h1 style="color: #000; "><strong>Correção das Postagens dos alunos</strong></h1>
                <h2 style="background-color: white; padding:12px; border-radius:20px; width:40%; text-align:center;">Total de solicitações pendentes: <?= $total_grupos_pendentes ?></h2>

                <?php if (!empty($grupos)): ?>
                    <?php foreach ($grupos as $grupo_id => $posts): ?>
                        <div class='grupo-container'>
                            <?php
                            $post = reset($posts);
                            ?>

                            <a
                                <?php if (!empty($post['img'])): ?>
                                style="background: url('../images/<?= htmlspecialchars($post['img']); ?>');"
                                <?php endif; ?>
                                class="post" href="./conteudo.php?id=<?php echo $post['id_solicitacoes'] ?>&ref=painel">
                                <p style="font-size: 20px; text-align: center;"><?= htmlspecialchars($post['titulo']); ?></p>
                                <p><?= htmlspecialchars($post['tema']); ?></p>
                            </a>

                            <form method="POST" action="painel.php">
                                <input type="hidden" name="grupo" value="<?= $grupo_id; ?>">
                                <button style="background: #6DC152;" type="submit" name="acao" value="aprovado"><i
                                        class="bi bi-check-lg"></i> APROVAR GRUPO</button>
                                <button style="background: #EA2D3F;" type="submit" name="acao" value="rejeitado"><i
                                        class="bi bi-x-lg"></i> REJEITAR GRUPO</button>
                                <button style="background: #FFBA00;" type="submit" name="acao" value="revisar"><i
                                        class="bi bi-pencil"></i> REVISAR GRUPO</button>
                            </form>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php else: ?>
                <p>Você não tem permissão para visualizar as solicitações de post.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Erro ao consultar permissões.</p>
        <?php endif; ?>


    </main>

    <footer style="margin-top: auto;">
        <div>
            <div class="d-flex logo" style="gap: 30px;">
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