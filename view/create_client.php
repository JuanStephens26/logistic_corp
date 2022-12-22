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
          document.form_hidden.action = "./create_client_frm.php";
          document.form_hidden.client_id.value = id;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <a href="./create_client_frm.php"><div class="btn btn-success">Crear clientes</div></a>
    <?php
   require_once("../class/cliente.php");

   $obj_clientes = new clientes();
   $cant_clientes = $obj_clientes->conteo_clientes();
   foreach ($cant_clientes as $result){
	$cantidad_clientes = $result['cantidad'];
   }

   $obj_clientes = new clientes();
   $clientes = $obj_clientes->consultar_clientes($pagina);

   $nfilas = 0;
   if(isset($clientes)){
   $nfilas=count($clientes);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de Clientes</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>Id</th>\n");
    print ("<TH scope='col'>Nombre</th>\n");
    print ("<TH scope='col'>Cedula/RUC</th>\n");
    print ("<TH scope='col'>Pais</th>\n");
    print ("<TH scope='col'>Ciudad</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($clientes as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['client_id']?>" onclick="abrir_modificacion('<?php echo $resultado['client_id']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['client_id']."</td>\n");
        print ("<TD>".$resultado['name']."</td>\n");
        print ("<TD>".$resultado['identification']."</td>\n");
        print ("<TD>".$resultado['country']."</td>\n");
        print ("<TD>".$resultado['city']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
      $total_pages = ceil($cantidad_clientes/5);
      $_GET['total_pages'] = $total_pages;
      include ($servidor_route."/template/pagination.php");
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="client_id">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay clientes registrados");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>