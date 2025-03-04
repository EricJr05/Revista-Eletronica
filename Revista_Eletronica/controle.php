<?php
include('./protect_adm.php');
include('./conexao.php');

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
    <title>Gerenciar Permissões</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand border border-light rounded border-2" href="./revista.php">
                <i class="bi bi-box-arrow-left text-light fs-3 m-3"></i>
            </a>
            <div class="ms-auto">
                <a class="navbar-brand" href="revista.php">Flow.UP</a>
            </div>
            <div class="ms-auto">
                <a class="btn btn-danger" href="logout.php">LOGOUT</a>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <h2 class="mb-4">Gerenciar Permissões de Usuários</h2>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-info"><?php echo $mensagem; ?></div>
        <?php endif; ?>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>