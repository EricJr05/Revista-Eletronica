<?php
include('../config/conexao.php');
include('../middleware/protect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = $mysqli->real_escape_string(trim($_POST['conteudo']));
    $data_expiracao = !empty($_POST['data_expiracao']) ? $mysqli->real_escape_string($_POST['data_expiracao']) : null;
    $id_professor = $_SESSION['id'];

    if (strlen($conteudo) > 1000) {
        die("Conteúdo excede o tamanho limite.");
    }

    if ($data_expiracao) {
        $sqli_code = "INSERT INTO avisos (conteudo, data_expira, id_usuario_aviso, data_aviso) 
                      VALUES ('$conteudo', '$data_expiracao', '$id_professor', NOW())";
    } else {
        $sqli_code = "INSERT INTO avisos (conteudo, id_usuario_aviso, data_aviso) 
                      VALUES ('$conteudo', '$id_professor', NOW())";
    }

    $sqli_query = $mysqli->query($sqli_code);

    if (!$sqli_query) {
        echo 'Erro ao Enviar: ' . $mysqli->error;
    } else {
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById("successModal"));
                myModal.show();
                setTimeout(function() {
                    window.location.href = "./criar_aviso.php";
                }, 2000);
            });
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./nav.css?v=1.2">
    <title>Criar Avisos</title>
    <style>
        body {
            background-color: #A7D4FF;
        }

        .check-circle {
            width: 100px;
            height: 100px;
            background-color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .check-circle i {
            font-size: 50px;
            color: white;
        }

        .yeah-text {
            color: #28a745;
            font-size: 40px;
            font-weight: bold;
            margin-top: 60px;
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
                        <li><a class="dropdown-item" href="./perfil.php?id=<?= htmlspecialchars($_SESSION['id']) ?>">Perfil</a></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Criar Novo Aviso</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="criar_aviso.php">
                    <div class="mb-3">
                        <label class="form-label">Aviso:</label>
                        <textarea name="conteudo" class="form-control" maxlength="1000" rows="4" required></textarea>
                        <div class="form-text">Máximo de 1000 caracteres.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Exibir até:</label>
                        <input type="date" name="data_expiracao" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success w-100">Publicar Aviso</button>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal position-relative p-4">
                <div class="modal-body text-center">
                    <div class="check-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h1 class="yeah-text">Aviso Adicionado!</h1>
                </div>
            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>