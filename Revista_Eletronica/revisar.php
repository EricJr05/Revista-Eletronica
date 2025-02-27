<?php

include('conexao.php');
include('protect_student.php');

$id_user = $_SESSION['id'];


if (!empty($_POST['titulo']) && !empty($_POST['conteudo']) && !empty($_POST['tema'])) {
    $titulo = $mysqli->real_escape_string($_POST['titulo']);
    $conteudo = $mysqli->real_escape_string($_POST['conteudo']);
    $tema = $mysqli->real_escape_string($_POST['tema']);
    $id_solicitacoes = $mysqli->real_escape_string($_POST['id_post']);

    $file_name = $_FILES['img']['name'];
    $tempname = $_FILES['img']['tmp_name'];
    $folder = 'images/' . $file_name ;

    if(isset($file_name)){
        $sqli_code = "UPDATE posts 
              SET titulo = '$titulo', conteudo = '$conteudo', tema = '$tema', status = 'pendente', data_solicitacao = NOW() , img = '$file_name' 
              WHERE id_solicitacoes = '$id_solicitacoes'";

        if(move_uploaded_file($tempname, $folder)){
            echo '<h2>File Upload</h2>';
        }else{
            echo '<h2>Erro</h2>';
        }
    }else{
        $sqli_code = "UPDATE posts 
              SET titulo = '$titulo', conteudo = '$conteudo', tema = '$tema', status = 'pendente', data_solicitacao = NOW() 
              WHERE id_solicitacoes = '$id_solicitacoes'";
    }

    $sqli_code = "UPDATE posts 
              SET titulo = '$titulo', conteudo = '$conteudo', tema = '$tema', status = 'pendente', data_solicitacao = NOW() 
              WHERE id_solicitacoes = '$id_solicitacoes'";


    $sqli_query = $mysqli->query($sqli_code);

    if ($sqli_query) {
        echo 'Revisão Enviada';
    } else {
        echo 'Erro ao Enviar: ' . $mysqli->error;
    }
}


$result = $mysqli->query("SELECT * FROM posts WHERE id_usuario_solicitacoes = '$id_user' AND status = 'revisar'");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar e Corrigir</title>
</head>

<body>
    <nav>
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>! Revisão de solicitações de post</h1>
        <a href="./revista.php">Voltar</a>
    </nav>

    <?php
    if ($result->num_rows > 0) {
        while ($revisao = $result->fetch_assoc()) {
            echo '<form method="POST" action="revisar.php">';
            echo '<label for="titulo">Título:</label>';
            echo '<input type="text" name="titulo" value="' . $revisao['titulo'] . '" required><br>';
            echo '<label for="img">Trocar Imagem:</label>';
            echo '<input type="file" name="img"><br>';
            echo "<img src='images/" . $solicitacao['img'] . "' />";

            echo '<label for="conteudo">Conteúdo:</label>';
            echo '<textarea name="conteudo" required>' . $revisao['conteudo'] . '</textarea><br>';

            echo '<label>Tema:</label><br>';
            $temas = [
                'FI' => 'Física',
                'LP' => 'Língua Portuguesa',
                'IN' => 'Língua Inglesa',
                'BIO' => 'Biologia',
                'MA' => 'Matemática',
                'GEO' => 'Geografia',
                'HI' => 'História',
                'TECNOLOGIA' => 'Tecnologia'
            ];

            foreach ($temas as $codigo => $nome) {
                $checked = ($revisao['tema'] == $codigo) ? 'checked' : '';
                echo '<label><input type="radio" name="tema" value="' . $codigo . '" ' . $checked . '> ' . $nome . '</label><br>';
            }

            echo '<input type="hidden" name="id_post" value="' . $revisao['id_solicitacoes'] . '">';
            echo '<br><button type="submit">Enviar Post Revisado</button>';
            echo '</form>';
            echo "<hr>";
        }
    } else {
        echo '<h2>NENHUMA SOLICITAÇÃO PENDENTE</h2>';
    }
    ?>
</body>

</html>