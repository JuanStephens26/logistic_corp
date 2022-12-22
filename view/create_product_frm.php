<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
        $prtnum = "";
        $name = "";
        $description = "";
        $lotnum = "";
        $client_id = "";
        $date_expirated = "";
        $date_manufacture = "";

        require_once("../class/producto.php");
        require_once("../class/cliente.php");

        $obj_clientes = new clientes();
        $clientes = $obj_clientes->consultar_cliente_all();

        if($_SERVER['REQUEST_METHOD']=='POST'){

            if(isset($_POST['indicador'])){
                $indicador = $_POST['indicador'];
            }

        if(isset($_POST['prtnum'])){
            $prtnum = $_POST['prtnum'];
            if($indicador == "CU"){
            $obj_productos = new productos();
            $productos = $obj_productos->sp_consultar_producto_id($prtnum);
            
            if(isset($productos)){
                foreach ($productos as $resultado){
                    $prtnum = $resultado["prtnum"];
                    $name = $resultado["name"];
                    $description = $resultado["description"];
                    $lotnum = $resultado["lotnum"];
                    $client_id = $resultado["client_id"];
                    $date_expirated = $resultado["date_expirated"];
                    $date_manufacture = $resultado["date_manufacture"];
                }
            }
        }
        }
    }
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); 
    
    if ($_SESSION["admin_flag"] == "S" || $_SESSION["client_flag"] == "S"){
    ?>
    <div class="table-responsive" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <form class="row" name="form_product" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Id</span>
                <input type="text" class="form-control" name="prtnum" value="<?php echo $prtnum ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Lote</span>
                <input type="text" class="form-control" name="lotnum" value="<?php echo $lotnum ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Nombre</span>
                <input type="text" class="form-control" name="name" value="<?php echo $name ?>">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Descripción</span>
                <input type="text" class="form-control" name="description" value="<?php echo $description ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Fecha Manufactura</span>
                <input type="date" class="form-control" id="date_manufacture" name="date_manufacture" value="<?php echo $date_manufacture; ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Fecha Expiración</span>
                <input type="date" class="form-control" id="date_expirated" name="date_expirated" value="<?php echo $date_expirated; ?>">
            </div>
        </div>
        <div class="col-md-4">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" >Cliente</span>
            <select class="form-select" name="client_id">
            <option value=""></option>
                <?php if(isset($clientes)){ 
                    foreach ($clientes as $resultado){
                    $selected = $resultado["client_id"] == $client_id ? "selected" : "";
                ?>
                <option <?php echo $selected; ?> value="<?php echo $resultado["client_id"]; ?>"><?php echo $resultado["name"]; ?></option>
                <?php } } ?>
            </select>
            </div>
        </div>
        <div class="col-md-12">
                    </div>
        <div class="col-md-2">
              <br>
              <a href="./create_product.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php 
        if($prtnum != ""){
        ?>    
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-danger form-control" onclick="eliminar_producto()">Eliminar</div>
        </div>
        <?php
        }
        ?>
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-primary form-control" onclick="guardar_producto()">Guardar</div>
        </div>
    </form>    
</div>
<script type="text/javascript" src="../js/create_product_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
