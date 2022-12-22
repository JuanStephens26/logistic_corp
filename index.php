<?php
 if(!isset($_SESSION)) 
 { 
     session_start(); 
 } 
 
    header("Location: ./view/login.php");

?>