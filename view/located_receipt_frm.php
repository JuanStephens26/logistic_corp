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
        $qty_located = "";
        $no_locc = "";
        $located_flag = "";
        $user_work = "";

        require_once("../class/entrada.php");
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
        $recibidores = $obj_usuarios->consultar_usuarios_ent();

        if($_SERVER['REQUEST_METHOD']=='POST' || $_SERVER['REQUEST_METHOD']=='GET'){

            if(isset($_REQUEST['indicador'])){
                $indicador = $_REQUEST['indicador'];
            }

            if(isset($_REQUEST['no_receipt'])){
                $id = $_REQUEST['no_receipt'];
            }

        if(isset($_REQUEST['no_receipt']) && isset($_REQUEST['no_line'])){
            $no_line = $_REQUEST['no_line'];
            if($indicador == "CU"){
            $obj_entradas = new entradas();
            $entradas = $obj_entradas->consultar_entrada_lin_id($id, $no_line);
            
            if(isset($entradas)){
                foreach ($entradas as $resultado){
                    $no_line = $resultado["no_line"];
                    $no_receipt = $resultado["no_receipt"];
                    $client_id = $resultado["client_id"];
                    $no_tempe = $resultado["no_tempe"];
                    $prtnum = $resultado["prtnum"];
                    $qty = $resultado["qty"];
                    $no_locc = $resultado["no_locc"];
                    $user_work = $resultado["user_work"];
                    $qty_located = $resultado["qty_located"];
                }
            }
        }
        }

        $obj_ubicaciones = new ubicaciones();
        $ubicaciones = $obj_ubicaciones->consultar_ubicaciones_rep($id, $no_line);
    }
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); 
    
    if ($_SESSION["admin_flag"] == "S" || $_SESSION["receiver_flag"] == "S"){
    ?>
    <div class="table-responsive" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <form class="row" name="form_receipt_lin" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >No. Entrada</span>
                <input type="text" class="form-control" name="no_receipt" value="<?php echo $id ?>" readonly>
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
                <span class="input-group-text" >Cantidad Ubicada</span>
                <input type="number" class="form-control" name="qty_located" value="<?php echo $qty ?>" readonly>
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
            <span class="input-group-text" >Temperatura</span>
            <select class="form-select" name="no_tempe" disabled>
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
            <span class="input-group-text" >Asignar a:</span>
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
              <a href="./located_receipt.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php if ($user_work == $_SESSION["usuario_valido"]){ ?>
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-primary form-control" onclick="located_receipt_lin()">Completar Tarea</div>
        </div>
        <?php } ?>
    </form>    
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_receipt">
      <input type="hidden" name="client_id">
      <input type="hidden" name="no_line">
      <input type="hidden" name="indicador">
    </form>
</div>
<script type="text/javascript" src="../js/create_receipt_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
