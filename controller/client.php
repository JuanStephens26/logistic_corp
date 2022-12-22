<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if (isset($_SESSION["usuario_valido"]))
{ 
if($_SERVER['REQUEST_METHOD']=='POST'){
	require_once("../class/cliente.php");

    $name = $_POST["name"];
    $identification = $_POST["identification"];
    $addres = $_POST["addres"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $client_id = $_POST["client_id"];
    $indicador = $_POST["indicador"];

	$obj_cliente = new clientes();
    $obj_cliente->client_id = $client_id;
    $obj_cliente->name = $name;
    $obj_cliente->identification = $identification;
    $obj_cliente->addres = $addres;
    $obj_cliente->country = $country;
    $obj_cliente->city = $city;
    $obj_cliente->indicador = $indicador;
    $obj_cliente->user_session = $_SESSION["usuario_valido"];
	$agregar_prv = $obj_cliente->agregar_cliente();
	
	header('Location: ../view/create_client.php');
}
}else{
    header('Location: ../index.php');
}
?>