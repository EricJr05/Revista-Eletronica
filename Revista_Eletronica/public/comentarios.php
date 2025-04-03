<?php
include '../config/conexao.php';
session_start();

$id_user = $_SESSION['id'];
$id_artigo = $_POST['id_post_like'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

if (!$id_user) {
    die("Erro: Você precisa estar logado para comentar.");
}

if (!$id_artigo || empty($comentario)) {
    die("Erro: Comentário inválido.");
}

$insert = $mysqli->prepare("INSERT INTO comentarios (post_id, user_id, conteudo) VALUES (?, ?, ?)");
$insert->bind_param("iis", $id_artigo, $id_user, $comentario);
$insert->execute();


header("Location: ../views/conteudo.php?id=" . $id_artigo);
?>