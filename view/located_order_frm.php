<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
        $id = "";
        $no_receipt = "";
        $client_id = "";
        $country = "";
        $city = "";
        $description = "";
        $status = "";
        $descri_status = "";
        $date_receipt = "";
        $no_line = "";
        $prtnum = "";
        $lotnum = "";
        $no_tempe = "";
        $qty = "";
        $qty_dispatched = "";
        $no_locc = "";
        $located_flag = "";
        $user_work = "";

        require_once("../class/pedido.php");
        require_once("../class/cliente.php");
        require_once("../class/producto.php");
        require_once("../class/temperatura.php");
        require_once("../class/ubicacion.php");
        require_once("../class/usuarios.php");

        $obj_clientes = new clientes();
        $clientes = $obj_clientes->consultar_cliente_all();

        $obj_productos = new productos();
        $obj_productos->client_id = $_SESSION["client_id"];
        $productos = $obj_productos->consultar_productos_all();

        $obj_temperaturas = new temperaturas();
        $temperaturas = $obj_temperaturas->consultar_temperatura_all();

        $obj_usuarios = new usuarios();
        $recibidores = $obj_usuarios->consultar_usuarios_des();

        if($_SERVER['REQUEST_METHOD']=='POST' || $_SERVER['REQUEST_METHOD']=='GET'){

            if(isset($_REQUEST['indicador'])){
                $indicador = $_REQUEST['indicador'];
            }

            if(isset($_REQUEST['no_order'])){
                $id = $_REQUEST['no_order'];
            }

        if(isset($_REQUEST['no_order']) && isset($_REQUEST['no_line'])){
            $no_line = $_REQUEST['no_line'];
            if($indicador == "CU"){
            $obj_pedidos = new pedidos();
            $pedidos = $obj_pedidos->consultar_order_lin_id($id, $no_line);
            
            if(isset($pedidos)){
                foreach ($pedidos as $resultado){
                    $no_line = $resultado["no_line"];
                    $no_receipt = $resultado["no_order"];
                    $client_id = $resultado["client_id"];
                    $prtnum = $resultado["prtnum"];
                    $qty = $resultado["qty"];
                    $no_locc = $resultado["no_locc"];
                    $user_work = $resultado["user_work"];
                    $qty_dispatched = $resultado["qty_dispatched"];
                }
            }
        }
        }

        $obj_ubicaciones = new ubicaciones();
        $ubicaciones = $obj_ubicaciones->consultar_ubicaciones_dis($id, $no_line);
    }
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); 
    
    if ($_SESSION["admin_flag"] == "S" || $_SESSION["dispatcher_flag"] == "S"){
    ?>
    <div class="table-responsive" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <form class="row" name="form_order_lin" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >No. Entrada</span>
                <input type="text" class="form-control" name="no_order" value="<?php echo $id ?>" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >No. Linea</span>
                <input type="text" class="form-control" name="no_line" value="<?php echo $no_line ?>" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Cantidad</span>
                <input type="number" class="form-control" name="qty" value="<?php echo $qty ?>" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group red input-group-sm mb-3">
                <span class="input-group-text" >Cantidad despachada</span>
                <input type="number" class="form-control" name="qty_dispatched" value="<?php echo $qty ?>" readonly>
            </div>
        </div>
        <div class="col-md-8">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" >Producto</span>
            <select class="form-select" name="prtnum" disabled>
            <option value=""></option>
                <?php if(isset($productos)){ 
                    foreach ($productos as $resultado){
                    $selected = $resultado["prtnum"] == $prtnum ? "selected" : "";
                ?>
                <option <?php echo $selected; ?> value="<?php echo $resultado["prtnum"] ?>"><?php echo $resultado["name"]; ?></option>
                <?php } } ?>
            </select>
            </div>
        </div>
        <div class="col-md-8">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" >Ubicaciones</span>
            <select class="form-select" name="no_locc" disabled>
            <option value=""></option>
                <?php if(isset($ubicaciones)){ 
                    foreach ($ubicaciones as $resultado){
                    $selected = $resultado["no_locc"] == $no_locc ? "selected" : "";
                ?>
                <option <?php echo $selected; ?> value="<?php echo $resultado["no_locc"]; ?>"><?php echo $resultado["no_locc"].' - '.$resultado["descri_loc"]; ?></option>
                <?php } } ?>
            </select>
        </div>
        </div>
        <div class="col-md-8">
        <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" >Asignado:</span>
            <select class="form-select" name="user_work" disabled>
            <option value=""></option>
                <?php if(isset($recibidores)){ 
                    foreach ($recibidores as $resultado){
                    $selected = $resultado["user"] == $user_work ? "selected" : "";
                ?>
                <option <?php echo $selected; ?> value="<?php echo $resultado["user"]; ?>"><?php echo $resultado["firts_name"].' '.$resultado["last_name"]; ?></option>
                <?php } } ?>
            </select>
        </div>
        </div>
        <div class="col-md-12">
                    </div>
        <div class="col-md-2">
              <br>
              <a href="./located_order.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php if ($user_work == $_SESSION["usuario_valido"]){ ?>
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-primary form-control" onclick="located_order_lin()">Completar Tarea</div>
        </div>
        <?php } ?>
    </form>    
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_order">
      <input type="hidden" name="client_id">
      <input type="hidden" name="no_line">
      <input type="hidden" name="indicador">
    </form>
</div>
<script type="text/javascript" src="../js/create_order_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
