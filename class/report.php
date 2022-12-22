<?php
    require_once('modelo.php');

    class reportes extends modeloCredencialesBD
    {
        public $client_id;

        public function __construct(){
            parent::__construct();
        }

        public function consultar_inventario_disponible(){
            $instruccion = "CALL sp_report_inventory_avaible('".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
        
        public function consultar_import(){
            $instruccion = "CALL sp_report_import('".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ixport(){
            $instruccion = "CALL sp_report_import('".$this->client_id."')";

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