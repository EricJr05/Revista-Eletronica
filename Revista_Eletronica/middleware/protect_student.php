<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    die('Você não está logado. <a href="../public/index.php">Entrar</a>');
}

if ($_SESSION['nivel'] == 1) {
    die('Essa página é reservada apenas para estudantes. <a href="../public/index.php">Entrar</a>');
}
