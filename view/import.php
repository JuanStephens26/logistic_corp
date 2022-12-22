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
    if ($_SESSION["client_flag"] == "S"){
    ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'create_receipt.php'"><img class="icon_image" src="../image/icon_create_import.png">Creación de entrada</div>
    <div class="w-100"></div>
    <?php } ?>
    <?php 
    if ($_SESSION["admin_flag"] == "S"){
      ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'asign_work_receipt.php'"><img class="icon_image" src="../image/icon_assign_user.png">Asignación de tarea</div>
    <div class="w-100"></div>
    <?php } ?>
    <?php 
    if ($_SESSION["receiver_flag"] == "S" || $_SESSION["admin_flag"] == "S"){
      ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'located_receipt.php'"><img class="icon_image" src="../image/icon_location_product.png">Ubicación de entrada</div>
      <?php } ?>
  </div>
</div>
    <?php 
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
    ?>