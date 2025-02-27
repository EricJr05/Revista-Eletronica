<?php
include('conexao.php');
include('protect.php');

$id_permissao = $_SESSION['nivel'];
$permissoes = $mysqli->query("SELECT p.* FROM usuarios u 
                              JOIN permissoes p ON u.id_permissoes_usuario = p.id 
                              WHERE p.id = $id_permissao");

// Atualizar status de um grupo inteiro
if (isset($_POST['acao']) && isset($_POST['grupo'])) {
    $grupo = intval($_POST['grupo']);
    $acao = $_POST['acao'];

    if (in_array($acao, ['aprovado', 'rejeitado', 'revisar'])) {
        $sqli_code = "UPDATE posts SET status = '$acao', data_solicitacao = NOW() WHERE grupo = $grupo";
        $sqli_query = $mysqli->query($sqli_code);

        if ($sqli_query) {
            echo 'Status do Grupo Atualizado Com Sucesso';
        } else {
            echo 'Erro ao atualizar status do grupo';
        }
    }
}

// Buscar posts pendentes agrupados
$result = $mysqli->query("SELECT post.*, user.nome 
                          FROM posts post
                          JOIN usuarios user ON post.id_usuario_solicitacoes = user.id 
                          WHERE post.status = 'pendente' 
                          ORDER BY post.grupo, post.data_solicitacao");

$grupos = [];
while ($solicitacao = $result->fetch_assoc()) {
    $grupos[$solicitacao['grupo']][] = $solicitacao;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <style>
        img {
            width: 200px;
            height: auto;
        }
        .grupo-container {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <a href="./revista.php">Visualizar a Revista</a> <br><br>

    <?php
    if ($permissoes) {
        $permissoes = $permissoes->fetch_assoc();

        if ($permissoes && $permissoes['visionar_post'] == 'S') {

            if (!empty($grupos)) {
                foreach ($grupos as $grupo_id => $posts) {
                    echo "<div class='grupo-container'>";
                    echo "<h2>Grupo: $grupo_id</h2>";

                    foreach ($posts as $solicitacao) {
                        echo "<p><strong>Título:</strong> " . htmlspecialchars($solicitacao['titulo']) . "</p>";
                        echo "<p><strong>Tema:</strong> " . htmlspecialchars($solicitacao['tema']) . "</p>";

                        if (!empty($solicitacao['img'])) {
                            echo "<img src='images/" . htmlspecialchars($solicitacao['img']) . "' />";
                        }

                        echo "<p><strong>Conteúdo:</strong> " . nl2br(htmlspecialchars($solicitacao['conteudo'])) . "</p>";
                        echo "<p><strong>Aluno:</strong> " . htmlspecialchars($solicitacao['nome']) . "</p>";
                        echo "<p><strong>Data Envio Post:</strong> " . htmlspecialchars($solicitacao['data_solicitacao']) . "</p>";

                        echo "<hr>";
                    }

                    // Botões de ação para o grupo inteiro
                    echo '<form method="POST" action="painel.php">';
                    echo '<input type="hidden" name="grupo" value="' . $grupo_id . '">';
                    echo '<button type="submit" name="acao" value="aprovado">✅ Aprovar Grupo</button> ';
                    echo '<button type="submit" name="acao" value="revisar">👁‍🗨 Revisar Grupo</button> ';
                    echo '<button type="submit" name="acao" value="rejeitado">❌ Rejeitar Grupo</button>';
                    echo '</form>';

                    echo "</div>";
                }
            } else {
                echo 'NENHUMA SOLICITAÇÃO PENDENTE';
            }
        } else {
            echo 'Você não tem permissão para visualizar as solicitações de post.';
        }
    } else {
        echo 'Erro ao consultar permissões.';
    }
    ?>

</body>

</html>
