<?php

function conectar(){
	$host="localhost";
	$bd = "piadas";
	$user = "root";
	$senha = "";

	$con = new mysqli($host, $user, $senha, $bd);
	return $con;

}

$conexao = conectar();


?>