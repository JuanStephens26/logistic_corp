<?php
 if(!isset($_SESSION)) 
 { 
     session_start(); 
 } 
if(isset($_REQUEST['usuario']) && isset($_REQUEST['clave'])){
    $usuario = $_REQUEST['usuario'];
    $clave = $_REQUEST['clave'];

    $salt = substr ($usuario, 0, 2);
    $clave_crypt = crypt ($clave, $salt);

    require_once("../class/usuarios.php");

    $obj_usuarios = new usuarios();
    $usuario_validado = $obj_usuarios->validar_usuario($usuario,$clave_crypt);

    if(isset($usuario_validado)){
        $nfilas=count($usuario_validado);

        if ($nfilas > 0){
        foreach ($usuario_validado as $array_resp) {
            $usuario_valido = $array_resp['user'];
            $admin_flag = $array_resp['admin_flag'];
            $receiver_flag = $array_resp['receiver_flag'];
            $dispatcher_flag = $array_resp['dispatcher_flag'];
            $client_flag = $array_resp['client_flag'];
            $client_id = $array_resp['client_id'];
        }
        //$usuario_valido = $usuario;
        $_SESSION["usuario_valido"] = $usuario_valido;
        $_SESSION["admin_flag"] = $admin_flag;
        $_SESSION["receiver_flag"] = $receiver_flag;
        $_SESSION["dispatcher_flag"] = $dispatcher_flag;
        $_SESSION["client_flag"] = $client_flag;
        $_SESSION["client_id"] = $client_id;
        }
    }
} // sesión iniciada
    if (isset($_SESSION["usuario_valido"]))
    {
        header('Location: ./home.php');
    // Intento de entrafa fallido
    } else {
        ?>
<!doctype html>
<!-- Representa la raíz de un documento HTML o XHTML. Todos los demás elementos deben ser descendientes de este elemento. -->
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title> Formulario de Acceso </title>    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Muestra de un formulario de acceso en HTML y CSS">
        <meta name="keywords" content="Formulario Acceso, Formulario de LogIn">
        <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css?family=Overpass&display=swap" rel="stylesheet">
        <!-- Link hacia el archivo de estilos css -->
        <link rel="stylesheet" href="../css/login.css">
        <style type="text/css">
        </style>
        <script type="text/javascript">
        </script>
    </head>
    <body>
        <div id="contenedor">
            <div id="central">
                <div id="login">
                    <div class="titulo">
                        Bienvenido
                    </div>
                    <form id="login" name="login" ACTION='./login.php' METHOD='POST'>
                        <input type="text" name="usuario" placeholder="Usuario" size='15' required>
                        
                        <input type="password" placeholder="Contraseña" name="clave" size='15' required>
                        
                        <button type="submit" value='entrar' title="Ingresar" name="Ingresar">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>         
    </body>
</html>
        <?php 
    }
    ?>
</body>
</html>

?>