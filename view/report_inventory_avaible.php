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
    if ($_SESSION["admin_flag"] == "S" || $_SESSION["client_flag"] == "S"){
    ?>
    <script type="text/javascript">
      function abrir_modificacion(id, no_line){
          document.form_hidden.action = "./located_receipt_frm.php";
          document.form_hidden.no_receipt.value = id;
          document.form_hidden.no_line.value = no_line;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <?php
   require_once("../class/report.php");

   $obj_reportes = new reportes();
   $obj_reportes->client_id = $_SESSION["client_id"];
   $reportes = $obj_reportes->consultar_inventario_disponible();

   $nfilas = 0;
   if(isset($reportes)){
   $nfilas=count($reportes);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Inventario disponible</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>Id. Producto</th>\n");
    print ("<TH scope='col'>Nombre</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Ubicacion</th>\n");
    print ("<TH scope='col'>descripcion Ubicacion</th>\n");
    print ("<TH scope='col'>Temperatura</th>\n");
    print ("<TH scope='col'>Cantidad</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($reportes as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['prtnum']?>">
        <?php
        print ("<Th scope='row'>".$resultado['prtnum']."</td>\n");
        print ("<TD>".$resultado['name']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
        print ("<TD>".$resultado['no_locc']."</td>\n");
        print ("<TD>".$resultado['descri_loc']."</td>\n");
        print ("<TD>".$resultado['descri_tempe']."</td>\n");
        print ("<TD>".$resultado['qty']."</td>\n");
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
      <input type="hidden" name="no_line">
      <input type="hidden" name="client_id">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay inventario");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>