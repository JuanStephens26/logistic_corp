<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if (isset($_SESSION["usuario_valido"]))
{ 
if($_SERVER['REQUEST_METHOD']=='POST'){
	require_once("../class/producto.php");

    $prtnum = $_POST["prtnum"];
    $lotnum = $_POST["lotnum"];
    $client_id = $_POST["client_id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $date_expirated = $_POST["date_expirated"];
    $date_manufacture = $_POST["date_manufacture"];
    $indicador = $_POST["indicador"];

	$obj_productos = new productos();
    $obj_productos->prtnum = $prtnum;
    $obj_productos->lotnum = $lotnum;
    $obj_productos->client_id = $client_id;
    $obj_productos->name = $name;
    $obj_productos->description = $description;
    $obj_productos->date_expirated = $date_expirated;
    $obj_productos->date_manufacture = $date_manufacture;
    $obj_productos->indicador = $indicador;
    $obj_productos->user_session = $_SESSION["usuario_valido"];
	$agregar_prv = $obj_productos->agregar_productos();
	
	header('Location: ../view/create_product.php');
}
}else{
    header('Location: ../index.php');
}
?>