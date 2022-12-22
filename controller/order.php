<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if (isset($_SESSION["usuario_valido"]))
{ 
if($_SERVER['REQUEST_METHOD']=='POST'){
	require_once("../class/pedido.php");

    $indicador = $_POST["indicador"];
    if($indicador == "I" || $indicador == "D"){
        $no_receipt = $_POST["no_receipt"];
        $client_id = $_POST["client_id"];
        $date_dispatched = $_POST["date_dispatched"];
        $description = $_POST["description"];
        
        $obj_pedidos = new pedidos();
        $obj_pedidos->user_session = $_SESSION["usuario_valido"];
        $obj_pedidos->no_order = $no_receipt;
        $obj_pedidos->client_id = $client_id;
        $obj_pedidos->date_dispatched = $date_dispatched;
        $obj_pedidos->description = $description;
        $obj_pedidos->indicador = $indicador;
        
	    $agregar_prv = $obj_pedidos->agregar_pedido();
        header('Location: ../view/create_order.php');
    }
    if($indicador == "LI" || $indicador == "LD"){
        $no_order = $_POST["no_order"];
        $no_line = $_POST["no_line"];
        $client_id = $_SESSION["client_id"];
        $prtnum = $_POST["prtnum"];
        $qty = $_POST["qty"];
        
        $obj_pedidos = new pedidos();
        $obj_pedidos->user_session = $_SESSION["usuario_valido"];
        $obj_pedidos->no_order = $no_order;
        $obj_pedidos->lclient_id = $client_id;
        $obj_pedidos->lno_line = $no_line;
        $obj_pedidos->lqty = $qty;
        $obj_pedidos->lprtnum = $prtnum;
        $obj_pedidos->indicador = $indicador;

        $agregar_prv = $obj_pedidos->agregar_order_lin();
        header('Location: ../view/create_order_frm.php?indicador=CU&no_order='.$no_order);
    }

    if($indicador == "AI"){
        $no_order = $_POST["no_order"];
        $no_line = $_POST["no_line"];
        $no_locc = $_POST["no_locc"];
        $user_work = $_POST["user_work"];
        
        $obj_pedidos = new pedidos();
        $obj_pedidos->user_session = $_SESSION["usuario_valido"];
        $obj_pedidos->no_order = $no_order;
        $obj_pedidos->lno_line = $no_line;
        $obj_pedidos->no_locc = $no_locc;
        $obj_pedidos->user_work = $user_work;
        $obj_pedidos->indicador = $indicador;

        $agregar_prv = $obj_pedidos->asignar_orden_lin();
        header('Location: ../view/asign_work_order.php');
    }

    if($indicador == "LO"){

        $no_order = $_POST["no_order"];
        $no_line = $_POST["no_line"];
        $qty_dispatched = $_POST["qty_dispatched"];
        
        $obj_pedidos = new pedidos();
        $obj_pedidos->user_session = $_SESSION["usuario_valido"];
        $obj_pedidos->no_order = $no_order;
        $obj_pedidos->lno_line = $no_line;
        $obj_pedidos->lqty_dispatched = $qty_dispatched;
        $obj_pedidos->indicador = $indicador;

        $agregar_prv = $obj_pedidos->asignar_orden_lin();
        header('Location: ../view/asign_work_receipt.php');
    }
 
}
}else{
    header('Location: ../index.php');
}
?>