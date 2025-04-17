<?php

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id']) || $_SESSION['nivel'] < 2){
    die('Você não pode acessar essa página pois não está logado. <a href="../public/index.php">Entrar</a>');
}
