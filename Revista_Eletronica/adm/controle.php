<?php
include('../middleware/protect_adm.php');
include('../config/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && isset($_POST['id_usuario'])) {
        $id_usuario = (int) $_POST['id_usuario'];

        if ($_POST['acao'] === 'alterar' && isset($_POST['nova_permissao'])) {
            $nova_permissao = (int) $_POST['nova_permissao'];
            $sql = "UPDATE usuarios SET id_permissoes_usuario = $nova_permissao WHERE id = $id_usuario";
            if ($mysqli->query($sql)) {
                $mensagem = "Permissão alterada com sucesso!";
            } else {
                $mensagem = "Erro ao alterar a permissão: " . $mysqli->error;
            }
        } elseif ($_POST['acao'] === 'excluir') {
            if ($id_usuario == $_SESSION['id']) {
                $mensagem = "Erro: Não é permitido excluir este usuário.";
            } else {
                $sql = "DELETE FROM usuarios WHERE id = $id_usuario";
                if ($mysqli->query($sql)) {
                    $mensagem = "Usuário excluído com sucesso!";
                } else {
                    $mensagem = "Erro ao excluir usuário: " . $mysqli->error;
                }
            }
        }
    }
}

$result = $mysqli->query("SELECT u.id, u.nome, u.email, p.perfil FROM usuarios u 
                          JOIN permissoes p ON u.id_permissoes_usuario = p.id");
$usuarios = $result->fetch_all(MYSQLI_ASSOC);

$result_permissoes = $mysqli->query("SELECT id, perfil FROM permissoes WHERE perfil != 'visitante'");
$permissoes = $result_permissoes->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../views/nav.css?v=1.3" rel="stylesheet">
    <title>Gerenciar Permissões</title>
    <style>
        body {
            background-color: rgb(174, 215, 253);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
        <div class="container-fluid">
            <a href="../views/revista.php" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <div class="logo">
                <a href="../view/revista.php">
                    <img src="../assets/LogoFlowUP.png" alt="Logo da Empresa Flow.UP">
                </a>
                <a href="../view/revista.php">
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


    <div class="container mt-5">

        <h1 style="color:#000556;" class="mb-5">Gerenciar Permissões de Usuários</h1>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-info"><?php echo $mensagem; ?></div>
        <?php endif; ?>
        <div class="table-responsive mb-5 mt-0">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Permissão Atual</th>
                        <th>Nova Permissão</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['perfil']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                    <select name="nova_permissao" class="form-select">
                                        <?php foreach ($permissoes as $permissao): ?>
                                            <option value="<?php echo $permissao['id']; ?>"
                                                <?php if ($permissao['perfil'] === $usuario['perfil']) echo 'selected'; ?>>
                                                <?php echo $permissao['perfil']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                            </td>
                            <td>
                                <button type="submit" name="acao" value="alterar" class="btn btn-primary btn-sm">Alterar</button>
                                <button type="submit" name="acao" value="excluir" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>