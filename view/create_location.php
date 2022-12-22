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
    if (isset($_SESSION["admin_flag"]) && $_SESSION["admin_flag"] == "S"){
    ?>
    <script type="text/javascript">
      function abrir_modificacion(id){
          document.form_hidden.action = "./create_location_frm.php";
          document.form_hidden.no_locc.value = id;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <a href="./create_location_frm.php"><div class="btn btn-success">Crear ubicación</div></a>
    <?php
   require_once("../class/ubicacion.php");

   $obj_ubicaciones = new ubicaciones();
   $cant_ubicaciones = $obj_ubicaciones->conteo_ubicaciones();
   foreach ($cant_ubicaciones as $result){
	$cantidad_ubicaciones = $result['cantidad'];
   }

   $obj_ubicaciones = new ubicaciones();
   $ubicaciones = $obj_ubicaciones->consultar_ubicaciones($pagina);

   $nfilas = 0;
   if(isset($ubicaciones)){
   $nfilas=count($ubicaciones);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de ubicaciones</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>Id</th>\n");
    print ("<TH scope='col'>Descripción</th>\n");
    print ("<TH scope='col'>Capacidad</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Lleno</th>\n");
    print ("<TH scope='col'>Temperatura</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($ubicaciones as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['no_locc']?>" onclick="abrir_modificacion('<?php echo $resultado['no_locc']?>');">
        <?php
        $full_flag = $resultado['full_flag'] == 'S' ? 'Si' : 'No';
        print ("<Th scope='row'>".$resultado['no_locc']."</td>\n");
        print ("<TD>".$resultado['descri_loc']."</td>\n");
        print ("<TD>".$resultado['capacity']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
        print ("<TD>".$full_flag."</td>\n");
        print ("<TD>".$resultado['descri_tempe']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
      $total_pages = ceil($cantidad_ubicaciones/5);
      $_GET['total_pages'] = $total_pages;
      include ($servidor_route."/template/pagination.php");
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="no_locc">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay ubicaciones registradas");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>