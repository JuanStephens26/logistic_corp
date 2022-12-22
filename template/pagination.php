<?php
 if(isset($_GET['pagina'])){
    $pagina = $_GET['pagina'];
}else {
    $pagina = 0;
}

if(isset($_GET['total_pages'])){
    $total_pages = $_GET['total_pages'];
}else {
    $total_pages = 0;
}
?>
<div align="center">
    <br />
    <nav aria-label="Page navigation example">
  <ul class="pagination">
    <?php 
    if($pagina > 0){
    ?>
    <li class="page-item">
      <a class="page-link" href="./create_user.php?pagina=<?php echo ($pagina-1)?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php 
    }
    for($i=1; $i<=$total_pages; $i++)
    {  
    ?>
    <li class="page-item"><a class="page-link" href="./create_user.php?pagina=<?php echo ($i-1)?>"><?php echo $i?></a></li>
    <?php 
    }
    if($pagina < ($total_pages - 1)){
    ?>
    <li class="page-item">
      <a class="page-link" href="./create_user.php?pagina=<?php echo ($pagina+1)?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
    <?php
    }
    ?>
  </ul>
</nav>
    </div>