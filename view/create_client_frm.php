<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
        $id = ""; 
        $name = ""; 
        $city = ""; 
        $addres = ""; 
        $country = "";
        $identification = ""; 

        require_once("../class/cliente.php");

        if($_SERVER['REQUEST_METHOD']=='POST'){

            if(isset($_POST['indicador'])){
                $indicador = $_POST['indicador'];
            }

        if(isset($_POST['client_id'])){
            $id = $_POST['client_id'];
            if($indicador == "CU"){
            $obj_clientes = new clientes();
            $clientes = $obj_clientes->consultar_cliente_id($id);
            if(isset($clientes)){
                foreach ($clientes as $resultado){
                    $id = $resultado["client_id"];
                    $name = $resultado["name"];
                    $city = $resultado["city"];
                    $addres = $resultado["addres"];
                    $country = $resultado["country"];
                    $identification = $resultado["identification"];
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
    <form class="row" name="form_client" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-2">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Id</span>
                <input type="text" class="form-control" name="client_id" value="<?php echo $id ?>" readonly>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Nombre</span>
                <input type="text" class="form-control" name="name" value="<?php echo $name ?>">
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Cédula / RUC</span>
                <input type="text" class="form-control" name="identification" value="<?php echo $identification ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >País</span>
                <input type="text" class="form-control" name="country" value="<?php echo $country ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Ciudad</span>
                <input type="test" class="form-control" name="city" value="<?php echo $city ?>">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Dirección</span>
                <input type="test" class="form-control" name="addres" value="<?php echo $addres ?>">
            </div>
        </div>
        <div class="col-md-2">
              <br>
              <a href="./create_client.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php 
        if($id != ""){
        ?>    
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-danger form-control" onclick="eliminar_usuario()">Eliminar</div>
        </div>
        <?php
        }
        ?>
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-primary form-control" onclick="guardar_usuario()">Guardar</div>
        </div>
    </form>    
</div>
<script type="text/javascript" src="../js/create_client_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
