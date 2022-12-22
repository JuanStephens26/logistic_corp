<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if (isset($_SESSION["usuario_valido"]))
{ 
if($_SERVER['REQUEST_METHOD']=='POST'){
	require_once("../class/entrada.php");

    $indicador = $_POST["indicador"];
    if($indicador == "I" || $indicador == "D"){
        $no_receipt = $_POST["no_receipt"];
        $client_id = $_POST["client_id"];
        $country = $_POST["country"];
        $city = $_POST["city"];
        $date_receipt = $_POST["date_receipt"];
        $description = $_POST["description"];
        
        $obj_entradas = new entradas();
        $obj_entradas->user_session = $_SESSION["usuario_valido"];
        $obj_entradas->no_receipt = $no_receipt;
        $obj_entradas->client_id = $client_id;
        $obj_entradas->country = $country;
        $obj_entradas->city = $city;
        $obj_entradas->date_receipt = $date_receipt;
        $obj_entradas->description = $description;
        $obj_entradas->indicador = $indicador;
        
	    $agregar_prv = $obj_entradas->agregar_entrada();
        //header('Location: ../view/create_receipt_frm.php?indicador=CU&no_receipt='.$no_receipt);
        header('Location: ../view/create_receipt.php');
    }
    if($indicador == "LI" || $indicador == "LD"){
        $no_receipt = $_POST["no_receipt"];
        $no_line = $_POST["no_line"];
        $no_tempe = $_POST["no_tempe"];
        $client_id = $_SESSION["client_id"];
        $prtnum = $_POST["prtnum"];
        $qty = $_POST["qty"];
        
        $obj_entradas = new entradas();
        $obj_entradas->user_session = $_SESSION["usuario_valido"];
        $obj_entradas->no_receipt = $no_receipt;
        $obj_entradas->lclient_id = $client_id;
        $obj_entradas->lno_line = $no_line;
        $obj_entradas->lqty = $qty;
        $obj_entradas->lno_tempe = $no_tempe;
        $obj_entradas->lprtnum = $prtnum;
        $obj_entradas->indicador = $indicador;

        $agregar_prv = $obj_entradas->agregar_entrada_lin();
        header('Location: ../view/create_receipt_frm.php?indicador=CU&no_receipt='.$no_receipt);
    }

    if($indicador == "AI"){
        $no_receipt = $_POST["no_receipt"];
        $no_line = $_POST["no_line"];
        $no_locc = $_POST["no_locc"];
        $user_work = $_POST["user_work"];
        
        $obj_entradas = new entradas();
        $obj_entradas->user_session = $_SESSION["usuario_valido"];
        $obj_entradas->no_receipt = $no_receipt;
        $obj_entradas->lno_line = $no_line;
        $obj_entradas->no_locc = $no_locc;
        $obj_entradas->user_work = $user_work;
        $obj_entradas->indicador = $indicador;

        $agregar_prv = $obj_entradas->asignar_entrada_lin();
        header('Location: ../view/asign_work_receipt.php');
    }

    if($indicador == "LO"){
        $no_receipt = $_POST["no_receipt"];
        $no_line = $_POST["no_line"];
        $qty_located = $_POST["qty_located"];
        
        $obj_entradas = new entradas();
        $obj_entradas->user_session = $_SESSION["usuario_valido"];
        $obj_entradas->no_receipt = $no_receipt;
        $obj_entradas->lno_line = $no_line;
        $obj_entradas->lqty_located = $qty_located;
        $obj_entradas->indicador = $indicador;

        $agregar_prv = $obj_entradas->asignar_entrada_lin();
        header('Location: ../view/asign_work_receipt.php');
    }
    
}
}else{
    header('Location: ../index.php');
}
?>