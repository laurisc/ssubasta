<?php
class sesion {
    function sesion($nombreSesion='PHPSESSID') {
        session_name($nombreSesion);
        if(!session_start()) {
            return FALSE;
        }
        return TRUE;
    }

    function registrarVariable($nombreVariable,$valor) {
        if(!($_SESSION[$nombreVariable] = $valor)) {
            return FALSE;
        }
        return TRUE;
    }

    function obtenerVariable($nombreVariable) {
        if (isset($_SESSION[$nombreVariable])) {
            return $_SESSION[$nombreVariable];
        } else {
            return FALSE;
        }     
    }

    function eliminarVariable($nombreVariable) {
        unset($_SESSION[$nombreVariable]);
        return TRUE;
    }
	
	
    function terminarSesion() {
       session_unset();
       session_destroy();
       return true;
    }
	
    function reiniciarSesion($redirectURL) {
        $this->terminarSesion();
         header("Cache-Control: no-cache, must-revalidate");
         header("Location:$redirectURL");
    }
	
	function redirecInicioSesion ($redirectURL) {
		if (!$this->obtenerVariable('id') || !$this->obtenerVariable('email') || !$this->obtenerVariable('datosActualizados') || !$this->obtenerVariable('usuario')) {
			$this->terminarSesion($redirectURL);
			header("Cache-Control: no-cache, must-revalidate");
			header("Location:$redirectURL");
			} 
		}
		
	function redirecInicioSesionRegistro ($redirectURL) {
		if (!$this->obtenerVariable('id') || !$this->obtenerVariable('email') || !$this->obtenerVariable('usuario')) {
			$this->terminarSesion($redirectURL);
			header("Cache-Control: no-cache, must-revalidate");
			header("Location:$redirectURL");
			} 
		}		
	
}	
?>