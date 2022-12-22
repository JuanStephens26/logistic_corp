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
    if ($_SESSION["client_flag"] == "S" || $_SESSION["admin_flag"] == "S"){
    ?>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'report_inventory_avaible.php'"><img class="icon_image" src="../image/icon_inventory_avaible.png">Inventario disponible</div>
    <div class="w-100"></div>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri" onclick="window.location = 'report_import.php'"><img class="icon_image" src="../image/icon_create_import.png">Entradas de productos</div>
    <div class="w-100"></div>
    <div class="col-2 list_menu div_padreh"></div>
    <div class="col-8 list_menu list_descri"><img class="icon_image" src="../image/icon_create_export.png">Salida de productos</div>
    <?php } ?>
  </div>
</div>
<?php 
      include ($servidor_route."/template/footer.php");
      } else { 
        header ("Location: ../index.php");
      }
?>