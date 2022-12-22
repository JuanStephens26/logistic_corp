<?php
    require_once('modelo.php');

    class usuarios extends modeloCredencialesBD
    {
        public $id;
        public $user;
        public $firts_name;
        public $last_name;
        public $password;
        public $admin_flag;
        public $receiver_flag;
        public $dispatcher_flag;
        public $client_flag;
        public $client_id;
        public $indicador;
        public $user_session;

        public function __construct(){
            parent::__construct();
        }

        public function validar_usuario($usr, $pwd){
            $instruccion = "CALL sp_validar_usuario('".$usr."','".$pwd."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_usuarios($pagina){
            $instruccion = "CALL sp_consultar_usuarios('".ceil($pagina * 5)."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_usuarios_ent(){
            $instruccion = "CALL sp_consutar_user_receiver()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_usuarios_des(){
            $instruccion = "CALL sp_consutar_user_dispatcher()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function conteo_usuarios(){
            $instruccion = "CALL sp_conteo_usuarios()";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }

        public function consultar_usuario_id($id){
            $instruccion = "CALL sp_consultar_usuario_id('".$id."')";

            $consulta=$this->_db->query($instruccion);
            $resultado=$consulta->fetch_all(MYSQLI_ASSOC);

            if($resultado){
                return $resultado;
                $resultado->close();
                $this->_db->close();
            }
        }
        
        public function agregar_usuario(){
            $instruccion = "CALL sp_guardar_usuario('".$this->user."',
                '".$this->firts_name."',
                '".$this->last_name."',
                '".$this->password."',
                '".$this->admin_flag."',
                '".$this->receiver_flag."',
                '".$this->dispatcher_flag."',
                '".$this->client_flag."',
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