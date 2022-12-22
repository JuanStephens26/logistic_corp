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
    if ($_SESSION["admin_flag"] == "S"){
    ?>
    <script type="text/javascript">
      function abrir_modificacion(id, no_line){
          document.form_hidden.action = "./asign_work_receipt_frm.php";
          document.form_hidden.no_receipt.value = id;
          document.form_hidden.no_line.value = no_line;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <?php
   require_once("../class/entrada.php");

   $obj_entradas = new entradas();
   $entradas = $obj_entradas->consultar_entradas_pendientes();

   $nfilas = 0;
   if(isset($entradas)){
   $nfilas=count($entradas);
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
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($entradas as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['no_receipt']?>" onclick="abrir_modificacion('<?php echo $resultado['no_receipt']?>', '<?php echo $resultado['no_line']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['no_receipt']."</td>\n");
        print ("<TD>".$resultado['no_line']."</td>\n");
        print ("<TD>".$resultado['descri_status']."</td>\n");
        print ("<TD>".$resultado['user_work']."</td>\n");
        print ("<TD>".$resultado['descri_loc']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
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
    print ("No hay recibo pendientes");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>