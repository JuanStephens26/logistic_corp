<?php
    require_once('modelo.php');

    class clientes extends modeloCredencialesBD
    {
        public $client_id;
        public $name;
        public $identification;
        public $addres;
        public $country;
        public $city;
        public $indicador;
        public $user_session;

        public function __construct(){
            parent::__construct();
        }

        public function consultar_clientes($pagina){
            $instruccion = "CALL sp_consultar_clientes('".ceil($pagina * 5)."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function conteo_clientes(){
            $instruccion = "CALL sp_conteo_clientes()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_cliente_id($id){
            $instruccion = "CALL sp_consultar_cliente_id('".$id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_cliente_all(){
            $instruccion = "CALL sp_consultar_clientes_all()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function agregar_cliente(){
            $instruccion = "CALL sp_guardar_client('".$this->client_id."',
                '".$this->name."',
                '".$this->identification."',
                '".$this->addres."',
                '".$this->country."',
                '".$this->city."',
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