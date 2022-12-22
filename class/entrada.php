<?php
    require_once('modelo.php');

    class entradas extends modeloCredencialesBD
    {
        public $no_receipt;
        public $client_id;
        public $country;
        public $city;
        public $description;
        public $status;
        public $date_receipt;
        public $lno_line;
        public $lprtnum;
        public $llotnum;
        public $lno_tempe;
        public $lqty;
        public $lqty_located;
        public $no_locc;
        public $located_flag;
        public $user_work;
        public $indicador;
        public $user_session;

        public function __construct(){
            parent::__construct();
        }

        public function consultar_entradas($pagina){
            $instruccion = "CALL sp_consultar_entradas('".ceil($pagina * 5)."', '".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_entradas_lineas(){
            $instruccion = "CALL sp_consultar_entradas_lineas('".$this->no_receipt."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function conteo_entradas(){
            $instruccion = "CALL sp_conteo_entradas('".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_entrada_id($id){
            $instruccion = "CALL sp_consultar_entrada_id('".$id."', '".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_entrada_lin_id($id, $no_line){
            $instruccion = "CALL sp_consultar_entrada_lin_id('".$id."', '".$no_line."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_entradas_pendientes(){
            $instruccion = "CALL sp_consultar_entradas_pendientes()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_entradas_asignadas(){
            $instruccion = "CALL sp_consultar_entradas_asignadas()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
        
        
        public function agregar_entrada(){
            $instruccion = "CALL sp_guardar_entrada('".$this->no_receipt."',
                '".$this->client_id."',
                '".$this->country."',
                '".$this->city."',
                '".$this->description."',
                '".$this->date_receipt."',
                '".$this->user_session."',
                '".$this->indicador."')";

            $consulta=$this->_db->query($instruccion);

            if($consulta){
                return $consulta;
                $consulta->close();
                $this->_db->close();
            }
        }

        public function agregar_entrada_lin(){
            $instruccion = "CALL sp_guardar_entrada_lin('".$this->no_receipt."',
                '".$this->lno_line."',
                '".$this->lqty."',
                '".$this->lclient_id."',
                '".$this->lprtnum."',
                '".$this->lno_tempe."',
                '".$this->user_session."',
                '".$this->indicador."')";

            $consulta=$this->_db->query($instruccion);

            if($consulta){
                return $consulta;
                $consulta->close();
                $this->_db->close();
            }
        }

        public function asignar_entrada_lin(){
            $instruccion = "CALL sp_asignar_entrada_lin('".$this->no_receipt."',
                '".$this->lno_line."',
                '".$this->no_locc."',
                '".$this->user_work."',
                '".$this->lqty_located."',
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