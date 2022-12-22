<?php if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (isset($_SESSION["usuario_valido"]))
    { 
$servidor_route = $_SERVER['DOCUMENT_ROOT']."/logistic_corp";
    ?>
    <?php include ($servidor_route."/template/header.php"); ?>
    <?php include ($servidor_route."/template/nav.php"); ?>
    <div class="container">
  <div class="row">
    <?php 
    if ($_SESSION["admin_flag"] == "S"){
    ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'create_user.php'"><img class="icon_image" src="../image/icon_create_user.png">Creación y modificación de usuarios</div>
    <div class="w-100"></div>
    <?php } ?>
    <?php 
    if ($_SESSION["admin_flag"] == "S"){
      ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'create_client.php'"><img class="icon_image" src="../image/icon_client.png">Creación y modificación de clientes</div>
    <div class="w-100"></div>
    <?php } ?>
    <?php 
    if ($_SESSION["admin_flag"] == "S"){
    ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'create_location.php'"><img class="icon_image" src="../image/icon_location.png">Creación y modificación de ubicaciones</div>
    <div class="w-100"></div>
    <?php } ?>
    <?php 
    if ($_SESSION["admin_flag"] == "S" || $_SESSION["client_flag"] == "S"){
    ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'create_product.php'"><img class="icon_image" src="../image/icon_product.png">Creación y modificación de productos</div>
    <div class="w-100"></div>
    <?php } ?>
  </div>
</div>
<?php 
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>