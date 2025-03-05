<?php

$host = 'localhost';
$user = 'root';
$senha = '';
$database = 'revista';

$mysqli = mysqli_connect($host,$user,$senha,$database);

if($mysqli->error){
    die('Erro na conexão');
}