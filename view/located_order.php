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
    if ($_SESSION["admin_flag"] == "S" || $_SESSION["dispatcher_flag"] == "S"){
    ?>
    <script type="text/javascript">
      function abrir_modificacion(id, no_line){
          document.form_hidden.action = "./located_order_frm.php";
          document.form_hidden.no_order.value = id;
          document.form_hidden.no_line.value = no_line;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <?php
   require_once("../class/pedido.php");

   $obj_pedidos = new pedidos();
   $pedidos = $obj_pedidos->consultar_ordenes_asignadas();

   $nfilas = 0;
   if(isset($pedidos)){
   $nfilas=count($pedidos);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Tareas Pendientes</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>No. Entradas</th>\n");
    print ("<TH scope='col'>No. Linea</th>\n");
    print ("<TH scope='col'>Estado</th>\n");
    print ("<TH scope='col'>Usuario Asignado</th>\n");
    print ("<TH scope='col'>Ubicacion</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Cantidad</th>\n");
    print ("<TH scope='col'>Cantidad recibida</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($pedidos as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['no_order']?>" onclick="abrir_modificacion('<?php echo $resultado['no_order']?>', '<?php echo $resultado['no_line']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['no_order']."</td>\n");
        print ("<TD>".$resultado['no_line']."</td>\n");
        print ("<TD>".$resultado['descri_status']."</td>\n");
        print ("<TD>".$resultado['user_work']."</td>\n");
        print ("<TD>".$resultado['descri_loc']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
        print ("<TD>".$resultado['qty']."</td>\n");
        print ("<TD>".$resultado['qty_dispatched']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_order">
      <input type="hidden" name="no_line">
      <input type="hidden" name="client_id">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay tareas pendientes");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>