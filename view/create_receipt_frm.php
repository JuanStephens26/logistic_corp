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
        $lno_line = "";
        $lprtnum = "";
        $llotnum = "";
        $lno_tempe = "";
        $lqty = "";
        $lqty_located = "";
        $no_locc = "";
        $located_flag = "";
        $user_work = "";

        require_once("../class/entrada.php");
        require_once("../class/cliente.php");

        $obj_clientes = new clientes();
        $clientes = $obj_clientes->consultar_cliente_all();

        if($_SERVER['REQUEST_METHOD']=='POST' || $_SERVER['REQUEST_METHOD']=='GET'){

            if(isset($_REQUEST['indicador'])){
                $indicador = $_REQUEST['indicador'];
            }

        if(isset($_REQUEST['no_receipt'])){
            $id = $_REQUEST['no_receipt'];
            if($indicador == "CU"){
            $obj_entradas = new entradas();
            $entradas = $obj_entradas->consultar_entrada_id($id);
            
            if(isset($entradas)){
                foreach ($entradas as $resultado){
                    $id = $resultado["no_receipt"];
                    $no_receipt = $resultado["no_receipt"];
                    $client_id = $resultado["client_id"];
                    $country = $resultado["country"];
                    $city = $resultado["city"];
                    $descri_status = $resultado["descri_status"];
                    $status = $resultado["status"];
                    $date_receipt = $resultado["date_receipt"];
                    $description = $resultado["description"];
                }
            }
        }
        }
    }
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); 
    
    if ($_SESSION["client_flag"] == "S"){
    ?>
    <div class="table-responsive" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <form class="row" name="form_receipt" method="post">
    <input type="hidden" name="indicador" >
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >No. Entrada</span>
                <input type="text" class="form-control" name="no_receipt" value="<?php echo $no_receipt ?>" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >País</span>
                <input type="text" class="form-control" name="country" value="<?php echo $country ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Ciudad</span>
                <input type="text" class="form-control" name="city" value="<?php echo $city ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" >Estado</span>
                <input type="text" class="form-control" name="descri_status" value="<?php echo $descri_status ?>" readonly>
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
                <span class="input-group-text" >Fecha entrada</span>
                <input type="date" class="form-control" id="date_receipt" name="date_receipt" value="<?php echo $date_receipt ?>">
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
              <a href="./create_receipt.php"><div class="btn btn-sm btn btn-secondary form-control">Cancelar</div></a>
        </div>
        <?php 
        if ( $status != 'A' && $status != 'F'){
        if($id != ""){
        ?>    
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-danger form-control" onclick="eliminar_receipt()">Eliminar</div>
        </div>
        <?php
        }
        ?>
        <div class="col-md-2">
              <br>
              <div class="btn btn-sm btn-primary form-control" onclick="guardar_receipt()">Guardar</div>
        </div>
        <?php
        }
        ?>
    </form>    
    <?php if(isset($_REQUEST['no_receipt'])){ ?>
        <a href="./create_receipt_lin_frm.php?no_receipt=<?php echo $no_receipt?>">
            <div class="btn btn-success">Añadir producto</div>
        </a>
    <?php
   require_once("../class/entrada.php");

   $obj_entradas = new entradas();
   $obj_entradas->client_id = $_SESSION["client_id"];
   $obj_entradas->no_receipt = $no_receipt;
   $entradas = $obj_entradas->consultar_entradas_lineas();

   $nfilas = 0;
   if(isset($entradas)){
   $nfilas=count($entradas);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de productos</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>No. Linea</th>\n");
    print ("<TH scope='col'>Producto</th>\n");
    print ("<TH scope='col'>Cantidad</th>\n");
    print ("<TH scope='col'>Temperatura</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($entradas as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['no_line']?>" onclick="abrir_modificacion_linea('<?php echo $resultado['no_line']?>','<?php echo $no_receipt?>');">
        <?php
        print ("<Th scope='row'>".$resultado['no_line']."</td>\n");
        print ("<TD>".$resultado['descri_prt']."</td>\n");
        print ("<TD>".$resultado['qty']."</td>\n");
        print ("<TD>".$resultado['descri_tempe']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_receipt">
      <input type="hidden" name="client_id">
      <input type="hidden" name="no_line">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay lineas");
   }
   ?> 
    <?php } ?>
</div>
<script type="text/javascript" src="../js/create_receipt_frm.js"></script>
    <?php 
      }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>
