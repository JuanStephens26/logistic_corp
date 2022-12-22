<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if (isset($_SESSION["usuario_valido"]))
{ 
if($_SERVER['REQUEST_METHOD']=='POST'){
	require_once("../class/usuarios.php");

    $user = $_POST["user"];
    $firts_name = $_POST["firts_name"];
    $last_name = $_POST["last_name"];
    $password = $_POST["password"];
    $admin_flag = isset($_POST["admin_flag"]) ? 'S' : 'N';
    $receiver_flag = isset($_POST["receiver_flag"]) ? 'S' : 'N';
    $dispatcher_flag = isset($_POST["dispatcher_flag"]) ? 'S' : 'N';
    $client_flag = isset($_POST["client_flag"]) ? 'S' : 'N';
    $client_id = $_POST["client_id"];
    $indicador = $_POST["indicador"];

    if($password != ""){
    $salt = substr ($user, 0, 2);
        $clave_crypt = crypt ($password, $salt);
    } else {
        $clave_crypt = "";
    }

	$obj_usuario = new usuarios();
    $obj_usuario->user = $user;
    $obj_usuario->firts_name = $firts_name;
    $obj_usuario->last_name = $last_name;
    $obj_usuario->password = $clave_crypt;
    $obj_usuario->admin_flag = $admin_flag;
    $obj_usuario->receiver_flag = $receiver_flag;
    $obj_usuario->dispatcher_flag = $dispatcher_flag;
    $obj_usuario->client_flag = $client_flag;
    $obj_usuario->client_id = $client_id;
    $obj_usuario->indicador = $indicador;
    $obj_usuario->user_session = $_SESSION["usuario_valido"];
	$agregar_prv = $obj_usuario->agregar_usuario();
	
	header('Location: ../view/create_user.php');
}
}else{
    header('Location: ../index.php');
}
?>