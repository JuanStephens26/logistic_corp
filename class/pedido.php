<?php
    require_once('modelo.php');

    class pedidos extends modeloCredencialesBD
    {
        public $no_order;
        public $client_id;
        public $country;
        public $city;
        public $description;
        public $status;
        public $date_dispatched;
        public $lno_line;
        public $lprtnum;
        public $llotnum;
        public $lno_tempe;
        public $lqty;
        public $lqty_dispatched;
        public $no_locc;
        public $located_flag;
        public $user_work;
        public $indicador;
        public $user_session;

        public function __construct(){
            parent::__construct();
        }

        public function consultar_ordenes($pagina){
            $instruccion = "CALL sp_consultar_ordenes('".ceil($pagina * 5)."', '".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ordenes_lineas(){
            $instruccion = "CALL sp_consultar_ordenes_lineas('".$this->no_order."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function conteo_ordenes(){
            $instruccion = "CALL sp_conteo_ordenes('".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ordenes_id($id){
            $instruccion = "CALL sp_consultar_ordenes_id('".$id."', '".$this->client_id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_order_lin_id($id, $no_line){
            $instruccion = "CALL sp_consultar_order_lin_id('".$id."', '".$no_line."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ordenes_pendientes(){
            $instruccion = "CALL sp_consultar_ordenes_pendientes()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_ordenes_asignadas(){
            $instruccion = "CALL sp_consultar_ordenes_asignadas()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
        
        
        public function agregar_pedido(){
            $instruccion = "CALL sp_guardar_orden('".$this->no_order."',
                '".$this->client_id."',
                '".$this->description."',
                '".$this->date_dispatched."',
                '".$this->user_session."',
                '".$this->indicador."')";

            $consulta=$this->_db->query($instruccion);

            if($consulta){
                return $consulta;
                $consulta->close();
                $this->_db->close();
            }
        }

        public function agregar_order_lin(){
            $instruccion = "CALL sp_guardar_order_lin('".$this->no_order."',
                '".$this->lno_line."',
                '".$this->lqty."',
                '".$this->lclient_id."',
                '".$this->lprtnum."',
                '".$this->user_session."',
                '".$this->indicador."')";

            $consulta=$this->_db->query($instruccion);

            if($consulta){
                return $consulta;
                $consulta->close();
                $this->_db->close();
            }
        }

        public function asignar_orden_lin(){
            $instruccion = "CALL sp_asignar_orden_lin('".$this->no_order."',
                '".$this->lno_line."',
                '".$this->no_locc."',
                '".$this->user_work."',
                '".$this->lqty_dispatched."',
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