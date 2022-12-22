<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
        $id = "";
        $descri_loc = "";
        $full_flag = "";
        $client_id = "";
        $no_tempe = "";
        $capacity = "";

        require_once("../class/ubicacion.php");
        require_once("../class/cliente.php");
        require_once("../class/temperatura.php");

        $obj_clientes = new clientes();
        $clientes = $obj_clientes->consultar_cliente_all();

        $obj_temperaturas = new temperaturas();
        $temperaturas = $obj_temperaturas->consultar_temperatura_all();

        if($_SERVER['REQUEST_METHOD']=='POST'){

            if(isset($_POST['indicador'])){
                $indicador = $_POST['indicador'];
            }

        if(isset($_POST['no_locc'])){
            $id = $_POST['no_locc'];
            if($indicador == "CU"){
            $obj_ubicaciones = new ubicaciones();
            $ubicaciones = $obj_ubicaciones->consultar_ubicacion_id($id);
            
            if(isset($ubicaciones)){
                foreach ($ubicaciones as $resultado){
                    $id = $resultado["no_locc"];
                    $descri_loc = $resultado["descri_loc"];
                    $full_flag = $resultado["full_flag"];
                    $client_id = $resultado["client_id"];
                    $no_tempe = $resultado["no_tempe"];
                    $capacity = $resultado["capacity"];
                }
            }
        }
        }
    }
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); 
    
    if (isset($_SESSION["admin_flag"]) && $_SESSION["admin_flag"] == "S"){
    ?>
    <div class="table-responsive" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <form class="row" name="form_location" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-4">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Id</span>
                <input type="text" class="form-control" name="no_locc" value="<?php echo $id ?>">
            </div>
        </div>
        <div class="col-md-8">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Nombre</span>
                <input type="text" class="form-control" name="descri_loc" value="<?php echo $descri_loc ?>">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Capacidad</span>
                <input type="number" class="form-control" name="capacity" value="<?php echo $capacity ?>">
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
        <div class="col-md-6">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" >Temperatura</span>
            <select class="form-select" name="no_tempe">
            <option value=""></option>
                <?php if(isset($temperaturas)){ 
                    foreach ($temperaturas as $resultado){
                    $selected = $resultado["no_tempe"] == $no_tempe ? "selected" : "";
                ?>
                <option <?php echo $selected; ?> value="<?php echo $resultado["no_tempe"]; ?>"><?php echo $resultado["descri_tempe"]; ?></option>
                <?php } } ?>
            </select>
                    </div>
        </div>
        <div class="col-md-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="full_flag" id="full_flag" <?php if($full_flag == 'S') {?>checked<?php } ?> disabled>
                <label class="form-check-label" for="full_flag">Lleno</label>
            </div>
        </div>
        <div class="col-md-2">
              <br>
              <a href="./create_location.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php 
        if($id != ""){
        ?>    
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-danger form-control" onclick="eliminar_ubicacion()">Eliminar</div>
        </div>
        <?php
        }
        ?>
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-primary form-control" onclick="guardar_ubicacion()">Guardar</div>
        </div>
    </form>    
</div>
<script type="text/javascript" src="../js/create_location_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
