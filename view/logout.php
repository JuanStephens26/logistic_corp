<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
        if (isset($_SESSION["usuario_valido"]))
        {
            session_destroy();
            header("Location: ../index.php");
        }
        else
        {
            header("Location: ../index.php");
        }
    ?>