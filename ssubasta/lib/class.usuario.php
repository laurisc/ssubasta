<?php
class usuario {
	var $tipoDocumento = '';
	var $documento = '';
	var $razonSocial = '';
	var $representanteLegal = '';
	var $actividadEconomica = '';
	var $direccion = '';
	var $ciudad = '';
	var $telefonoUno = '';
	var $telefonoDos = '';
//	var $email = '';
	
	function usuario ($tipoDocumento, $documento, $razonSocial, $representanteLegal, $actividadEconomica, $direccion, $ciudad, $telefonoUno, $telefonoDos ) {
		$this->tipoDocumento = $tipoDocumento;
		$this->documento = $documento;
		$this->razonSocial = (isset($razonSocial)) ? $razonSocial : '' ;
		$this->representanteLegal = $representanteLegal;
		$this->actividadEconomica = $actividadEconomica;
		$this->direccion = $direccion;
		$this->ciudad = $ciudad;
		$this->telefonoUno = $telefonoUno;
		$this->telefonoDos = $telefonoDos;
//		$this->email = $email;
//		return $this->validaUsuario ();		
		}	
		
	function validarUsuario () {
		return true;
		if (empty($this->tipoDocumento)) return false;
		if (empty($this->documento)) return false;
		if (empty($this->razonSocial)) return false;
		if (empty($this->representanteLegal)) return false;
		if (empty($this->actividadEconomica)) return false;
		if (empty($this->direccion)) return false;
		if (empty($this->ciudad)) return false;
		if (empty($this->telefonoUno)) return false;
		if (empty($this->telefonoDos)) return false;
//		if (empty($this->email)) return false;
		
		
			
		}
	
	
	
	}
	
?>