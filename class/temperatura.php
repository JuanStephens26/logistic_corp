<?php
    require_once('modelo.php');

    class temperaturas extends modeloCredencialesBD
    {

        public function __construct(){
            parent::__construct();
        }

        public function consultar_temperatura_all(){
            $instruccion = "CALL sp_consultar_temperaturas_all()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
    }
    
?>