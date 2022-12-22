<?php
    require_once('modelo.php');

    class productos extends modeloCredencialesBD
    {
        
        public $prtnum;
        public $name;
        public $description;
        public $lotnum;
        public $client_id;
        public $date_manufacture;
        public $date_expirated;
        public $indicador;

        public function __construct(){
            parent::__construct();
        }

        public function consultar_productos($pagina){
            $instruccion = "CALL sp_consultar_producto('".ceil($pagina * 5)."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function conteo_productos(){
            $instruccion = "CALL sp_conteo_producto()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function sp_consultar_producto_id($id){
            $instruccion = "CALL sp_consultar_producto_id('".$id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
        public function consultar_productos_all(){
            $instruccion = "CALL sp_consultar_producto_all('".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function agregar_productos(){
            $instruccion = "CALL sp_guardar_producto('".$this->prtnum."',
                '".$this->name."',
                '".$this->description."',
                '".$this->lotnum."',
                '".$this->client_id."',
                '".$this->date_manufacture."',
                '".$this->date_expirated."',
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