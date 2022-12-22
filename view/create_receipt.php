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
          document.form_hidden.action = "./create_receipt_frm.php";
          document.form_hidden.no_receipt.value = id;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <a href="./create_receipt_frm.php"><div class="btn btn-success">Crear entrada</div></a>
    <?php
   require_once("../class/entrada.php");

   $obj_entradas = new entradas();
   $obj_entradas->client_id = $_SESSION["client_id"];
   $cant_entradas = $obj_entradas->conteo_entradas();
   foreach ($cant_entradas as $result){
	$cantidad_entradas = $result['cantidad'];
   }

   $obj_entradas = new entradas();
   $obj_entradas->client_id = $_SESSION["client_id"];
   $entradas = $obj_entradas->consultar_entradas($pagina);

   $nfilas = 0;
   if(isset($entradas)){
   $nfilas=count($entradas);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de entradas</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>No. Entradas</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Estado</th>\n");
    print ("<TH scope='col'>Fecha de recepción</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($entradas as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['no_receipt']?>" onclick="abrir_modificacion('<?php echo $resultado['no_receipt']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['no_receipt']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
        print ("<TD>".$resultado['descri_status']."</td>\n");
        print ("<TD>".$resultado['date_receipt']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
      $total_pages = ceil($cantidad_entradas/5);
      $_GET['total_pages'] = $total_pages;
      include ($servidor_route."/template/pagination.php");
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_receipt">
      <input type="hidden" name="client_id">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay recibo registrados");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>