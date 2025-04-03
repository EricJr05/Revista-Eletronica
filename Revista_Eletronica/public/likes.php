<?php
include '../config/conexao.php';
session_start();

$id_user = $_SESSION['id'];

$id_solicitacoes = $_GET['ref'] ?? null;

$check_artigo = $mysqli->prepare("SELECT id_solicitacoes FROM posts WHERE id_solicitacoes = ?");
$check_artigo->bind_param("i", $id_solicitacoes);
$check_artigo->execute();
$result_artigo = $check_artigo->get_result();

if ($result_artigo->num_rows === 0) {
    die("Erro: Artigo não encontrado.");
}

$check_user = $mysqli->prepare("SELECT id FROM usuarios WHERE id = ?");
$check_user->bind_param("i", $id_user);
$check_user->execute();
$result_user = $check_user->get_result();

if ($result_user->num_rows === 0) {
    die("Erro: Usuário não encontrado.");
}

$query = $mysqli->prepare("SELECT * FROM likes WHERE id_post_like = ? AND id_usuario_like = ?");
$query->bind_param("ii", $id_solicitacoes, $id_user);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $delete = $mysqli->prepare("DELETE FROM likes WHERE id_post_like = ? AND id_usuario_like = ?");
    $delete->bind_param("ii", $id_solicitacoes, $id_user);
    $delete->execute();
} else {
    $insert = $mysqli->prepare("INSERT INTO likes (id_post_like, id_usuario_like) VALUES (?, ?)");
    $insert->bind_param("ii", $id_solicitacoes, $id_user);
    $insert->execute();
}

header("Location: ../views/conteudo.php?id=" . $id_solicitacoes);
exit();
