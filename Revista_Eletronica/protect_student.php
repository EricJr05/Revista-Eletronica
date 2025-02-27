<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    die('Você não está logado. <a href="index.php">Entrar</a>');
}

if ($_SESSION['nivel'] == 1) {
    die('Essa página é reservada apenas para estudantes. <a href="index.php">Entrar</a>');
}

if ($_SESSION['nivel'] > 2) {
    echo 'Você pode realizar post diretos sem necessidade de permissão. <a href="painel.php">Voltar</a>';
}