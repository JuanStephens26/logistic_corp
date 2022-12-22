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
          document.form_hidden.action = "./create_user_frm.php";
          document.form_hidden.user_id.value = id;
          document.form_hidden.indicador.value = "CU";
          document.form_hidden.submit();
      }
    </script>
    <div class="table-responsive-sm" style="height:80%; padding-top: 20px; padding-left: 20px; padding-right: 20px;">
    <a href="./create_user_frm.php"><div class="btn btn-success">Crear usuario</div></a>
    <?php
   require_once("../class/usuarios.php");

   $obj_cusuarios = new usuarios();
   $cant_usuarios = $obj_cusuarios->conteo_usuarios();
   foreach ($cant_usuarios as $result){
	$cantidad_usuarios = $result['cantidad'];
   }

   $obj_usuarios = new usuarios();
   $usuarios = $obj_usuarios->consultar_usuarios($pagina);

   $nfilas = 0;
   if(isset($usuarios)){
   $nfilas=count($usuarios);
   }
   
    print ("<TABLE class='table table-striped table-hover'>\n");
    print ("<caption>Lista de usuarios</caption>\n");
    print ("<thead class='table-dark'>\n");
    print ("<TR>\n");
    print ("<TH scope='col'>User</th>\n");
    print ("<TH scope='col'>Nombre</th>\n");
    print ("<TH scope='col'>Administrador</th>\n");
    print ("<TH scope='col'>Despachador</th>\n");
    print ("<TH scope='col'>Recibe</th>\n");
    print ("<TH scope='col'>Cliente</th>\n");
    print ("<TH scope='col'>Id Cliente</th>\n");
    print ("</TR>\n");
    print ("</thead>\n");
    if ($nfilas > 0){
    foreach ($usuarios as $resultado){
        $admin_flag = $resultado['admin_flag'] == 'S' ? 'Si' : 'No';
        $receiver_flag = $resultado['receiver_flag'] == 'S' ? 'Si' : 'No';
        $dispatcher_flag = $resultado['dispatcher_flag'] == 'S' ? 'Si' : 'No';
        $client_flag = $resultado['client_flag'] == 'S' ? 'Si' : 'No';
        print ("<tbody>\n");
        ?>
        <TR style='cursor:pointer;' id="<?php echo $resultado['user']?>" onclick="abrir_modificacion('<?php echo $resultado['user']?>');">
        <?php
        print ("<Th scope='row'>".$resultado['user']."</td>\n");
        print ("<TD>".$resultado['firts_name']." ".$resultado['last_name']."</td>\n");
        print ("<TD>".$admin_flag."</td>\n");
        print ("<TD>".$receiver_flag."</td>\n");
        print ("<TD>".$dispatcher_flag."</td>\n");
        print ("<TD>".$client_flag."</td>\n");
        print ("<TD>".$resultado['client_id']."</td>\n");
        print ("</TR>\n");
        print ("</tbody>\n");
    }
  }
    print("</table>\n");
    if ($nfilas > 0){
      $total_pages = ceil($cantidad_usuarios/5);
      $_GET['total_pages'] = $total_pages;
      include ($servidor_route."/template/pagination.php");
 ?>
    </div>
    <form name="form_hidden" hidden="true" method="post">
      <input type="hidden" name="user_id">
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