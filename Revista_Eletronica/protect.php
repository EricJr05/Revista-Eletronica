<?php

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id']) || $_SESSION['nivel'] < 3){
    die('Você não pode acessar essa página pois não está logado. <a href="index.php">Entrar</a>');
}
