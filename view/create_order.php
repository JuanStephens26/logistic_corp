<?php if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
        if(isset($_GET['pagina'])){
            $pagina = $_GET['pagina'];
        }else {
            $pagina = 0;
        }
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); 
    if ($_SESSION["client_flag"] == "S"){
    ?>
    <script type="text/javascript">
      function abrir_modificacion(id){
          document.form_hidden.action = "./create_order_frm.php";
          document.form_hidden.no_order.value = id;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <a href="./create_order_frm.php"><div class="btn btn-success">Crear pedido</div></a>
    <?php
   require_once("../class/pedido.php");

   $obj_pedidos = new pedidos();
   $obj_pedidos->client_id = $_SESSION["client_id"];
   $cant_pedidos = $obj_pedidos->conteo_ordenes();
   foreach ($cant_pedidos as $result){
	$cantidad_pedidos = $result['cantidad'];
   }

   $obj_pedidos = new pedidos();
   $obj_pedidos->client_id = $_SESSION["client_id"];
   $pedidos = $obj_pedidos->consultar_ordenes($pagina);

   $nfilas = 0;
   if(isset($pedidos)){
   $nfilas=count($pedidos);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de ordenes</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>No. Orden</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Estado</th>\n");
    print ("<TH scope='col'>Fecha de despacho</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($pedidos as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['no_order']?>" onclick="abrir_modificacion('<?php echo $resultado['no_order']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['no_order']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
        print ("<TD>".$resultado['descri_status']."</td>\n");
        print ("<TD>".$resultado['date_dispatched']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
      $total_pages = ceil($cantidad_pedidos/5);
      $_GET['total_pages'] = $total_pages;
      include ($servidor_route."/template/pagination.php");
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_order">
      <input type="hidden" name="client_id">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay ordenes registrados");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>