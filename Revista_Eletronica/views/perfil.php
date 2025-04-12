<?php
include('../middleware/protect_student.php');
include('../config/conexao.php');


$ref = $_GET['id'] ?? null;
$id = $ref;
$result = $mysqli->query("SELECT * FROM posts WHERE status = 'aprovado' AND id_usuario_solicitacoes = $id ORDER BY data_solicitacao DESC");

$me = true;
if ($id != $_SESSION['id']) {
    $me = false;
}


$grupos = [];
if ($result && $result->num_rows > 0) {
    while ($pagina = $result->fetch_assoc()) {
        $grupos[$pagina['grupo']][] = $pagina;
    }
}

$userQuery = $mysqli->query("SELECT * FROM usuarios WHERE id = $id");
$user = $userQuery->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $bio = $_POST['bio'];

    $mysqli->query("UPDATE usuarios SET nome = '$nome', bio = '$bio' WHERE id = $id");
    $_SESSION['nome'] = $nome;
    $_SESSION['bio'] = $bio;

    header('location: ./perfil.php');
}

if (!empty($_FILES["foto"]["name"])) {
    $fotoNome = $_FILES["foto"]["name"];
    $fotoTemp = $_FILES["foto"]["tmp_name"];
    $fotoDestino = "uploads/" . uniqid() . "_" . $fotoNome;

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    if (move_uploaded_file($fotoTemp, $fotoDestino)) {
        $mysqli->query("UPDATE usuarios SET perfil_foto = '$fotoDestino' WHERE id = $id");
        $_SESSION['foto'] = $fotoDestino;
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./nav.css">
    <title>Revisar Post</title>

    <style>
        body {
            background: #A7D4FF;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        nav {
            width: 100%;
        }

        .container {
            margin-top: 20px;
        }

        form .dados label {
            background-color: #222661;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 5px 10px;
            border-radius: 50px;
            display: inline-block;
        }

        form .dados input {
            height: 60px;
            width: 800px;
            box-shadow: 3px 3px 2px rgba(0, 0, 0, .3);
            font-size: 20px;
        }

        hr {
            width: 68%;
            background: white;
            height: 2px;
            border-radius: 20px;
            border: none;
            opacity: 1;
        }

        #historico {
            background: white;
            border: 2px solid #171D71;
            padding: 14px;
            border-radius: 20px;
            height: 70px;
            overflow: hidden;
        }

        #historico.open {
            height: auto;
        }

        #historico .titulo h1 {
            margin-bottom: 20px;
            color: #171D71;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        #historico .titulo span {
            display: inline-block;
            width: 0;
            height: 0;
            border-top: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 30px solid #171D71;
            transition: transform .1s ease-in-out;
        }

        #historico.open .titulo span {
            transform: rotate(90deg);
        }

        #conteudo {
            border: none;
        }

        #conteudo div {
            box-shadow: 3px 3px 6px rgba(0, 0, 0, .7);
            border-radius: 20px;
            border: 1px solid #d9d9d9;
            transition: transform .3s ease-in;
        }

        #conteudo div:hover {
            transform: scale(1.01);
        }

        #conteudo div a {
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        button {
            width: 100%;
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
                        <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id']) ?>">Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>


    <div class="container">
        <form method="POST" class="d-flex justify-content-between align-items-center m-3" enctype="multipart/form-data">
            <div class="mb-3 text-center">
                <label for="foto" class="d-block">
                    <?php if (!empty($user['perfil_foto'])): ?>
                        <img src="<?= htmlspecialchars($user['perfil_foto']) ?>" alt="Foto de perfil" class="rounded-circle" width="250" height="250" style="object-fit: cover; cursor: pointer;">
                    <?php else: ?>
                        <i class="bi bi-person-circle" style="font-size: 200px; cursor: pointer;"></i>
                    <?php endif; ?>
                </label>
                <input type="file" id="foto" name="foto" class="d-none" accept="image/*" <?= !$me ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3 dados">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input <?= !$me ? 'disabled' : '' ?> type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>">
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Biografia</label>
                    <input <?= !$me ? 'disabled' : '' ?> type="text" id="bio" name="bio" class="form-control" value="<?= htmlspecialchars($user['bio']) ?>">
                </div>

                <?= !$me ? '' : '<button type="submit" class="btn btn-success">Atualizar</button>' ?>

            </div>

        </form>
    </div>

    <hr>

    <div id="historico" class="container mt-5">
        <div onclick="toggleHistorico()" class="d-flex justify-content-between titulo">
            <h1>Histórico de Postagens</h1>
            <span></span>
        </div>
        <?php foreach ($grupos as $grupo => $posts): ?>
            <?php $post = reset($posts); ?>
            <div id="conteudo" class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0"><?= htmlspecialchars($post['titulo']) ?></h5>
                    <a href="conteudo.php?id=<?= $post['id_solicitacoes'] ?>" class="text-success ft-1 text-decoration-none">+ Ler Mais</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector("#foto").addEventListener("change", function() {
            if (this.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let imgElement = document.querySelector("label[for='foto'] img");
                    if (!imgElement) {
                        imgElement = document.createElement("img");
                        imgElement.classList.add("rounded-circle");
                        imgElement.width = 200;
                        imgElement.height = 200;
                        imgElement.style.objectFit = "cover";
                        document.querySelector("label[for='foto']").appendChild(imgElement);
                    }
                    imgElement.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        document.querySelector("label[for='foto']").addEventListener("click", function() {
            document.querySelector("#foto").click();
        });

        function toggleHistorico() {
            const historico = document.querySelector("#historico");
            historico.classList.toggle("open");
        }
    </script>
</body>

</html>