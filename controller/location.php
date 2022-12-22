<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if (isset($_SESSION["usuario_valido"]))
{ 
if($_SERVER['REQUEST_METHOD']=='POST'){
	require_once("../class/ubicacion.php");

    $no_locc = $_POST["no_locc"];
    $descri_loc = $_POST["descri_loc"];
    $capacity = $_POST["capacity"];
    $no_tempe = $_POST["no_tempe"];
    $client_id = $_POST["client_id"];
    $indicador = $_POST["indicador"];

	$obj_ubicaciones = new ubicaciones();
    $obj_ubicaciones->no_locc = $no_locc;
    $obj_ubicaciones->descri_loc = $descri_loc;
    $obj_ubicaciones->capacity = $capacity;
    $obj_ubicaciones->no_tempe = $no_tempe;
    $obj_ubicaciones->client_id = $client_id;
    $obj_ubicaciones->indicador = $indicador;
    $obj_ubicaciones->user_session = $_SESSION["usuario_valido"];
	$agregar_prv = $obj_ubicaciones->agregar_ubicaciones();
	
	header('Location: ../view/create_location.php');
}
}else{
    header('Location: ../index.php');
}
?>