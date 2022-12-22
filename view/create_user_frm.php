<?php if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
        $id = "";
        $user = "";
        $firts_name = "";
        $last_name = "";
        $password = "";
        $admin_flag = "";
        $receiver_flag = "";
        $dispatcher_flag = "";
        $client_flag = "";
        $client_id = "";
        $indicador = "";

        require_once("../class/usuarios.php");
        require_once("../class/cliente.php");

        $obj_clientes = new clientes();
        $clientes = $obj_clientes->consultar_cliente_all();

        if($_SERVER['REQUEST_METHOD']=='POST'){

            if(isset($_POST['indicador'])){
                $indicador = $_POST['indicador'];
            }

        if(isset($_POST['user_id'])){
            $id = $_POST['user_id'];
            if($indicador == "CU"){
            $obj_usuarios = new usuarios();
            $usuarios = $obj_usuarios->consultar_usuario_id($id);
            if(isset($usuarios)){
                foreach ($usuarios as $resultado){
                    $user = $resultado["user"];
                    $firts_name = $resultado["firts_name"];
                    $last_name = $resultado["last_name"];
                    $password = $resultado["password"];
                    $admin_flag = $resultado["admin_flag"];
                    $receiver_flag = $resultado["receiver_flag"];
                    $dispatcher_flag = $resultado["dispatcher_flag"];
                    $client_flag = $resultado["client_flag"];
                    $client_id = $resultado["client_id"];
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
    <form class="row" name="form_user" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-4">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Usuario</span>
                <input type="text" class="form-control" name="user" value="<?php echo $user ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Nombre</span>
                <input type="text" class="form-control" name="firts_name" value="<?php echo $firts_name ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Apellido</span>
                <input type="text" class="form-control" name="last_name" value="<?php echo $last_name ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Contraseña</span>
                <input type="password" class="form-control" name="password" value="">
            </div>
        </div>
        <div class="col-md-6">
        </div>
        <!-- div class="col-md-6">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Confirmar contraseña</span>
                <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </div>
        </div-->
        <div class="col-md-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="admin_flag" id="admin_flag" <?php if($admin_flag == 'S') {?>checked<?php } ?>>
                <label class="form-check-label" for="admin_flag">Administrador</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="receiver_flag" id="receiver_flag" <?php if($receiver_flag == 'S') {?>checked<?php } ?>>
                <label class="form-check-label" for="receiver_flag">Recibe</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="dispatcher_flag" id="dispatcher_flag" <?php if($dispatcher_flag == 'S') {?>checked<?php } ?>>
                <label class="form-check-label" for="dispatcher_flag">Despacha</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="client_flag" id="client_flag" <?php if($client_flag == 'S') {?>checked<?php } ?>>
                <label class="form-check-label" for="client_flag">Cliente</label>
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
        <div class="col-md-2">
              <br>
              <a href="./create_user.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php 
        if($user != ""){
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
<script type="text/javascript" src="../js/create_user_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
