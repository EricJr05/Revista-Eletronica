<?php

$host = '10.188.34.134';
$user = 'root';
$senha = 'root';
$database = 'revista_flow';

$mysqli = mysqli_connect($host,$user,$senha,$database);

if($mysqli->error){
    die('Erro na conexão');
}