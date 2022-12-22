<?php
    require_once('modelo.php');

    class ubicaciones extends modeloCredencialesBD
    {
        
        public $no_locc;
        public $descri_loc;
        public $capacity;
        public $no_tempe;
        public $client_id;
        public $indicador;

        public function __construct(){
            parent::__construct();
        }

        public function consultar_ubicaciones($pagina){
            $instruccion = "CALL sp_consultar_location('".ceil($pagina * 5)."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function conteo_ubicaciones(){
            $instruccion = "CALL sp_conteo_location()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ubicacion_id($id){
            $instruccion = "CALL sp_consultar_location_id('".$id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ubicaciones_rep($no_receipt, $no_line){
            $instruccion = "CALL sp_consultar_ubicaciones_rep('".$no_receipt."','".$no_line."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
        
        public function consultar_ubicaciones_dis($no_order, $no_line){
            $instruccion = "CALL sp_consultar_ubicaciones_dis('".$no_order."','".$no_line."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function agregar_ubicaciones(){
            $instruccion = "CALL sp_guardar_location('".$this->no_locc."',
                '".$this->descri_loc."',
                '".$this->capacity."',
                '".$this->no_tempe."',
                '".$this->client_id."',
                '".$this->user_session."',
                '".$this->indicador."')";

            $consulta=$this->_db->query($instruccion);

            if($consulta){
                return $consulta;
                $consulta->close();
                $this->_db->close();
            }
        }
    }
    
?>