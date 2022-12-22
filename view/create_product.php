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
      function abrir_modificacion(id){
          document.form_hidden.action = "./create_product_frm.php";
          document.form_hidden.prtnum.value = id;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <a href="./create_product_frm.php"><div class="btn btn-success">Crear producto</div></a>
    <?php
   require_once("../class/producto.php");

   $obj_productos = new productos();
   $cant_productos = $obj_productos->conteo_productos();
   foreach ($cant_productos as $result){
	$cantidad_productos = $result['cantidad'];
   }

   $obj_productos = new productos();
   $productos = $obj_productos->consultar_productos($pagina);

   $nfilas = 0;
   if(isset($productos)){
   $nfilas=count($productos);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de productos</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>Id</th>\n");
    print ("<TH scope='col'>Nombre</th>\n");
    print ("<TH scope='col'>Lote</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Fecha de manufactura</th>\n");
    print ("<TH scope='col'>Fecha de expiración</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($productos as $resultado){
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['prtnum']?>" onclick="abrir_modificacion('<?php echo $resultado['prtnum']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['prtnum']."</td>\n");
        print ("<TD>".$resultado['name']."</td>\n");
        print ("<TD>".$resultado['lotnum']."</td>\n");
        print ("<TD>".$resultado['descri_client']."</td>\n");
        print ("<TD>".$resultado['date_manufacture']."</td>\n");
        print ("<TD>".$resultado['date_expirated']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
      $total_pages = ceil($cantidad_productos/5);
      $_GET['total_pages'] = $total_pages;
      include ($servidor_route."/template/pagination.php");
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="prtnum">
      <input type="hidden" name="indicador">
    </form>
    <?php
}
   else{
    print ("No hay productos registrados");
   }
   ?> 
<?php 
    }
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>